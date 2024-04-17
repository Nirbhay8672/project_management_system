import Iodine from "@caneara/iodine";
import { reactive, toRef, toRefs, isRef, unref, isReactive } from "vue";

function withParamsAndMessage(params, message) {
    return { params: params.params, message };
}

function withParams(...val) {
    let values = [];
    val.forEach((value) => {
        if (Array.isArray(value)) {
            values.push(toRef(value[0], value[1]));
        } else {
            values.push(value);
        }
    });

    return { params: values };
}

let additionalValidations = {
    required: {
        callback: (value) => {
            if (value === "" || value === undefined || value === null) {
                return false;
            }

            if (Array.isArray(value)) {
                return value.length > 0;
            }

            if (typeof value === "object") {
                return Object.keys(value).length > 0;
            }

            return true;
        },
        message: "Value is required",
    },
    requiredIf: {
        callback: (value, param) => {
            let params = param.split(",");

            if (params[0].toString().trim().length === 0) {
                return true;
            }

            if (params.length === 1) {
                return value && value.toString().trim().length > 0;
            }

            if (params.length === 2 && params[0] == params[1]) {
                return value && value.toString().trim().length > 0;
            }

            return true;
        },
        message: "Required when other value is present",
    },
    unique: {
        callback: (value, param) => {
            let params = param.split(",");
            return (
                params.filter((x) => x.toString() === value.toString())
                    .length <= 1
            );
        },
        message: "Value is not unique",
    },
    customMaxLength: {
        callback: (value, param) => {},
        message: "Custom Max Length Message",
    },
};

class FormValidation {
    rules = reactive({});
    errors = reactive({});
    validationState = reactive({
        valid: true,
    });
    input = null;
    iodine = null;
    disabledRulesForFields = [];

    constructor(input, rules = {}) {
        this.iodine = new Iodine();

        if (isReactive(input)) {
            this.input = toRefs(input);
        }

        if (isRef(input)) {
            this.input = toRef(input);
        }

        this.loadAdditionalCustomValidator();

        this.addFields(this.input, rules, "");
    }

    loadAdditionalCustomValidator = function () {
        for (const [ruleName, obj] of Object.entries(additionalValidations)) {
            this.addCustomRule(ruleName, obj.callback, obj.message);
        }
    };

    addCustomRule = function (ruleName, callback, message) {
        this.iodine.rule(ruleName, callback);
        this.iodine.setErrorMessage(ruleName, message);
    };

    addFields = function (path, rules = {}, as = "") {
        for (const [key, rulesArray] of Object.entries(rules)) {
            this.addField(path, key, rulesArray, as);
        }
    };

    addField = function (path, key, rules, as = null) {
        let modifiedRules = [];
        let customKey = as ? `${as}.${key}` : key;

        if (Array.isArray(rules)) {
            rules.forEach((rule) => {
                modifiedRules.push({
                    rule,
                    message: null,
                    params: null,
                });
            });
        } else if (typeof rules === "object") {
            for (const [rule, details] of Object.entries(rules)) {
                if (typeof details === "object") {
                    let { params, message } = details;
                    modifiedRules.push({
                        rule,
                        message: message,
                        params: params,
                    });
                }

                if (
                    typeof details === "string" ||
                    typeof details === "function"
                ) {
                    modifiedRules.push({
                        rule,
                        message: details,
                        params: null,
                    });
                }
            }
        }

        if (this.hasField(customKey)) {
            modifiedRules.forEach((rule) => {
                let index = this.rules[customKey].rules.findIndex((obj) => {
                    return obj.rule === rule.rule;
                });

                if (index >= 0) {
                    this.rules[customKey].rules[index] = rule;
                } else {
                    this.rules[customKey].rules.push(rule);
                }
            });
        } else {
            this.rules[customKey] = {
                key: key,
                as: customKey,
                input: path,
                rules: modifiedRules,
            };
        }
    };

    getFieldsAndMessages = function (rules) {
        let iodineRules = [];
        let messages = {};

        let getMessage = function (message) {
            if (typeof message === "function") {
                return message();
            }

            return message;
        };

        rules.forEach((ruleObj) => {
            if (ruleObj.params) {
                let params = [];
                ruleObj.params.forEach((param) => {
                    if (isRef(param)) {
                        params.push(unref(param));
                    } else if (typeof param === "function") {
                        params.push(param());
                    } else {
                        params.push(param);
                    }
                });

                iodineRules.push(`${ruleObj.rule}:${params.join(",")}`);

                if (ruleObj.message) {
                    messages[`${ruleObj.rule}:${params.join(",")}`] =
                        getMessage(ruleObj.message);
                }
            } else {
                iodineRules.push(`${ruleObj.rule}`);
                if (ruleObj.message) {
                    messages[ruleObj.rule] = getMessage(ruleObj.message);
                }
            }
        });

        return { rules: iodineRules, messages };
    };

    hasError(key) {
        return this.errors.hasOwnProperty(key);
    }

    getError(key) {
        if (this.hasError(key)) {
            return this.errors[key];
        }

        return "";
    }

    hasField = function (key) {
        return this.rules.hasOwnProperty(key);
    };

    disableField = (...keys) => {
        const callback = (key) => {
            if (!this.disabledRulesForFields.includes(key)) {
                this.disabledRulesForFields.push(key);
            }
        };

        if (Array.isArray(keys)) {
            keys.forEach((key) => callback(key));
        }

        if (typeof keys === "string") {
            callback(keys);
        }
    };

    enableField = (keys) => {
        const callback = (key) => {
            if (this.disabledRulesForFields.includes(key)) {
                this.disabledRulesForFields.splice(
                    this.disabledRulesForFields.indexOf(key),
                    1
                );
            }
        };

        if (Array.isArray(keys)) {
            keys.forEach((key) => callback(key));
        }

        if (typeof keys === "string") {
            callback(keys);
        }
    };

    removeField = function (key, rule = "") {
        if (!this.hasField(key)) {
            return;
        }

        if (rule) {
            let ruleIndex = this.rules[key].rules.findIndex((obj) => {
                return obj.rule === rule;
            });

            if (ruleIndex >= 0) {
                this.rules[key].rules.splice(ruleIndex, 1);
            }
        }

        if (!rule) {
            delete this.rules[key];
        }
    };

    removeFields = function (prefix) {
        for (const [key, obj] of Object.entries(this.rules)) {
            if (key.startsWith(prefix)) {
                this.removeField(key);
            }
        }
    };

    reset() {
        this.errors = {};
        this.validationState.valid = true;
    }
    validate = function () {
        this.reset();
        let disableFields = this.disabledRulesForFields;

        for (const [key, obj] of Object.entries(this.rules)) {
            if (!disableFields.includes(key)) {
                let { rules, messages } = this.getFieldsAndMessages(obj.rules);

                let validated = this.iodine.assert(obj.input[obj.key], rules);

                if (this.validationState.valid !== true) {
                    this.validationState.valid = false;
                }

                if (validated.valid === false) {
                    this.errors[obj.as] = [
                        messages[validated.rule] || validated.error,
                    ];
                }
            }
        }
    };

    isValid = function () {
        return Object.keys(this.errors).length === 0;
    };

    setServerSideErrors(errors) {
        this.errors = [];
        this.errors = errors;
    }
}

export { FormValidation, withParamsAndMessage, withParams };
