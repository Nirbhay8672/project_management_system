<template>
    <inertia-head title="Users" />
    <main-page>
        <div class="container-fluid p-0 mb-3">
            <div class="row mb-2 gy-3">
                <div class="col-sm-6">
                    <h5 class="d-inline align-middle">Users</h5>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-end gy-3">
                        <button
                            class="btn btn-primary btn-sm ms-sm-3 ms-md-3 ms-lg-3 mt-sm-2 mt-3 mt-md-0 mt-lg-0 mt-sm-0"
                            @click="openForm()"
                            v-if="hasPermission('add_user')"
                        >
                            <i class="fa fa-plus-circle"></i>
                            <span class="ms-2">Add User</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-body p-4"
                        v-if="loader"
                        style="height: 200px"
                    >
                        <div class="overflow dark" id="preload">
                            <div class="circle-line">
                                <div class="circle-red"><b>P</b></div>
                                <div class="circle-blue"><b>M</b></div>
                                <div class="circle-red"><b>S</b></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4" v-else>
                        <div class="row mt-2 justify-content-between gy-3 mb-3">
                            <div class="col-md-auto me-auto">
                                <div class="dt-length">
                                    <select
                                        class="form-select form-control"
                                        id="per_page"
                                        v-model="fields.per_page"
                                        @change="chnageMainFilter()"
                                    >
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-auto ms-auto">
                                <div class="dt-search">
                                    <input
                                        type="text"
                                        id="search_input"
                                        placeholder="Search..."
                                        class="form-control"
                                        v-model="fields.search"
                                        @keyup="chnageMainFilter()"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="table-responsive">
                                <table
                                    class="table"
                                    style="
                                        border-collapse: separate;
                                        border-spacing: 0 10px;
                                    "
                                >
                                    <thead>
                                        <tr
                                            style="
                                                background-color: rgb(
                                                    201 201 201
                                                );
                                            "
                                        >
                                            <th>Sr No.</th>
                                            <th>Profile</th>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="users.length > 0">
                                            <template
                                                v-for="(user, index) in users"
                                                :key="`user_${index}`"
                                            >
                                                <user-row
                                                    :user="user"
                                                    :index="index"
                                                    @openEditForm="
                                                        openForm(user)
                                                    "
                                                    @deleteUser="
                                                        deleteUser(user)
                                                    "
                                                    @openView="openView(user)"
                                                    :auth="auth"
                                                ></user-row>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr
                                                style="width: 100%"
                                                class="text-center"
                                            >
                                                <td colspan="6">
                                                    <img
                                                        alt=""
                                                        src="../../../../../../images/no_found.png"
                                                        style="width: 300px"
                                                    />
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row gy-3" v-if="users.length > 0">
                            <div class="col-md-auto me-auto">
                                <div>
                                    Showing {{ fields.start_index }} to
                                    {{ fields.end_index }} of
                                    {{ fields.total_record }} Results
                                </div>
                            </div>
                            <div class="col-md-auto ms-auto">
                                <div class="dt-paging paging_full_numbers">
                                    <ul class="pagination">
                                        <li class="page-item" @click="prev()">
                                            <span class="page-link"
                                                ><i
                                                    class="fa fa-angle-double-left"
                                                ></i
                                            ></span>
                                        </li>
                                        <template
                                            v-for="page in fields.total_pages"
                                            :key="`page_${page}`"
                                        >
                                            <li
                                                class="page-item"
                                                :class="
                                                    page === fields.page
                                                        ? 'active'
                                                        : ''
                                                "
                                                @click="setPage(page)"
                                            >
                                                <span
                                                    class="page-link cursor-pointer"
                                                    >{{ page }}</span
                                                >
                                            </li>
                                        </template>

                                        <li class="page-item" @click="next()">
                                            <span class="page-link"
                                                ><i
                                                    class="fa fa-angle-double-right"
                                                ></i
                                            ></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <teleport to="body">
            <user-form
                ref="user_form"
                :grouped_permissions="grouped_permissions"
                @reload="reloadTable"
            />
            <user-view
                ref="user_view"
                :grouped_permissions="grouped_permissions"
            />
        </teleport>
    </main-page>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import UserRow from "./includes/UserRow.vue";
import axios from "axios";
import { userRoutes } from "../../routes/UserRoutes";
import userForm from "./includes/Form.vue";
import userView from "./includes/View.vue";
import { confirmAlert, toastAlert } from "../../helpers/alert";

let users = ref([]);
let loader = ref(true);

let user_form = ref(null);
let user_view = ref(null);

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    grouped_permissions: {
        type: Object,
        required: true,
    },
});

let fields = reactive({
    search: "",
    per_page: 10,
    total_record: 0,
    total_pages: 1,
    page: 1,
    start_index: 0,
    end_index: 0,
});

onMounted(() => {
    setTimeout(function () {
        reloadTable();
    }, 1000);
});

function openForm(user = null) {
    user_form.value.openModal(user);
}

function openView(user = null) {
    user_view.value.openModal(user);
}

function deleteUser(user) {
    confirmAlert({
        title: "Delete",
        icon: "question",
        html: `Are you sure, you want to delete <strong> ${user.username} </strong> user ?`,
    }).then((result) => {
        if (result.isConfirmed) {
            axios
                .get(userRoutes.deleteUser(user.id))
                .then((response) => {
                    toastAlert({ title: response.data.message });
                    reloadTable();
                })
                .catch(function (error) {
                    if (error.response.status === 422) {
                        toastAlert({
                            title: error.response.data.message,
                            icon: "error",
                        });
                    }
                });
        }
    });
}

function chnageMainFilter() {
    fields.page = 1;
    reloadTable();
}

function setPage(page_number) {
    fields.page = page_number;
    reloadTable();
}

function prev() {
    if (fields.page === 1) {
        return;
    }
    fields.page--;
    reloadTable();
}

function next(page_number) {
    if (fields.page === fields.total_pages) {
        return;
    }
    fields.page++;
    reloadTable();
}

function reloadTable() {
    axios
        .post(userRoutes.datatable, fields)
        .then((response) => {
            users.value = response.data.users;
            fields.total_record = response.data.total;
            fields.total_pages = response.data.total_pages;
            fields.start_index = response.data.start_index;
            fields.end_index = response.data.end_index;
            loader.value = false;
        })
        .catch(function (error) {
            if (error.response.status === 422) {
                console.log("somthing went wrong");
            }
        });
}

function hasPermission(permission_name) {
    let permission_obj = props.auth.user.permissions.find(
        (permission) => permission.name == permission_name
    );

    return permission_obj ? true : false;
}
</script>
