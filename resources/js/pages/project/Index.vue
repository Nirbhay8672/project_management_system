<template>
    <inertia-head title="Projects" />
    <main-page>
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <div class="mr-auto">
                    <h5 class="d-inline align-middle">Projects</h5>
                </div>
                <div>
                    <button class="btn btn-secondary btn-sm">
                        <i class="fa fa-refresh"></i>
                        <span class="ms-2">Re-Sync</span>
                    </button>
                    <button class="btn btn-primary btn-sm ms-3">
                        <i class="fa fa-plus-circle"></i>
                        <span class="ms-2">Add Website</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row gy-3">
                            <div class="col-12 col-lg-3 col-md-6 col-sm-6">
                                <input
                                    type="text"
                                    id=""
                                    placeholder="Search..."
                                    class="form-control"
                                />
                            </div>
                            <div class="col-12 col-lg-2 col-md-4 col-sm-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text"
                                            id="basic-addon1"
                                            ><span
                                                ><i
                                                    class="fa fa-calendar"
                                                ></i></span
                                        ></span>
                                    </div>
                                    <select
                                        name="duration"
                                        id="duration"
                                        class="form-select"
                                    >
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <div class="card bg-light">
                                                <div
                                                    class="card-body"
                                                    style="
                                                        padding: 0rem 0rem !important;
                                                        margin-bottom: -12px;
                                                    "
                                                >
                                                    <div class="row">
                                                        <div
                                                            style="width: 5%"
                                                            class="text-center"
                                                        >
                                                            <i
                                                                class="text-success fa fa-arrow-up"
                                                            ></i>
                                                            <p>
                                                                <small
                                                                    >Up</small
                                                                >
                                                            </p>
                                                        </div>
                                                        <div style="width: 5%">
                                                            <img
                                                                src="http://project_management_system.test/storage/user/1/user_17125726010.png"
                                                                class="rounded"
                                                                style="
                                                                    height: 35px;
                                                                    width: auto;
                                                                "
                                                                alt="profile image"
                                                            />
                                                        </div>

                                                        <div style="width: 20%">
                                                            <small
                                                                >Project
                                                                Name</small
                                                            >
                                                            <p>
                                                                <small
                                                                    ><i
                                                                        class="fa fa-external-link fs-8"
                                                                    ></i
                                                                    ><span
                                                                        class="text-primary ms-1"
                                                                        >https://example.com</span
                                                                    ></small
                                                                >
                                                            </p>
                                                        </div>
                                                        <div
                                                            style="
                                                                width: 1%;
                                                                border-left: 2px
                                                                    solid
                                                                    #e8e8e8;
                                                                height: 51px;
                                                            "
                                                        ></div>
                                                        <div style="width: 10%">
                                                            <small>
                                                                <img
                                                                    src="../../../../../../images/google.png"
                                                                    style="
                                                                        height: 15px;
                                                                        width: auto;
                                                                    "
                                                                    alt="profile image"
                                                                />
                                                                <span
                                                                    class="ms-2"
                                                                    >96/100</span
                                                                >
                                                            </small>
                                                            <p class="mt-1">
                                                                <i
                                                                    class="fa fa-tachometer text-danger fs-8"
                                                                ></i>
                                                                <small
                                                                    class="ms-2"
                                                                    >1203ms</small
                                                                >
                                                            </p>
                                                        </div>
                                                        <div style="width: 10%">
                                                            <span
                                                                class="badge rounded-pill p-2 text-dark"
                                                                style="
                                                                    background-color: #ff00004d;
                                                                "
                                                                ><div
                                                                    class="rounded-circle bg-warning"
                                                                ></div>
                                                                <i
                                                                    class="fa fa-cog"
                                                                ></i
                                                                ><span
                                                                    class="ms-1"
                                                                ></span>
                                                                15 Updates</span
                                                            >
                                                        </div>
                                                        <div style="width: 13%">
                                                            <i
                                                                class="fa fa-history text-success"
                                                            ></i>
                                                            <small class="ms-2"
                                                                >Backup
                                                                active</small
                                                            >
                                                        </div>
                                                        <div style="width: 11%">
                                                            <i
                                                                class="fa fa-heart text-success"
                                                            ></i>
                                                            <small class="ms-2"
                                                                >1 Site
                                                                helth</small
                                                            >
                                                        </div>
                                                        <div style="width: 10%">
                                                            <img
                                                                src="../../../../../../images/php.png"
                                                                style="
                                                                    height: 15px;
                                                                    width: auto;
                                                                "
                                                                alt="profile image"
                                                            />
                                                            <small class="ms-2"
                                                                >1 Site
                                                                helth</small
                                                            >
                                                        </div>
                                                        <div style="width: 14%">
                                                            <button
                                                                class="btn btn-light bg-white border"
                                                            >
                                                                <img
                                                                    src="../../../../../../images/wordpres.png"
                                                                    class="rounded"
                                                                    style="
                                                                        height: 20px;
                                                                        width: auto;
                                                                    "
                                                                    alt="wp"
                                                                />
                                                                <small
                                                                    class="ms-2"
                                                                    >WP
                                                                    Admin</small
                                                                >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main-page>
</template>
