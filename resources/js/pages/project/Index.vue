<template>
    <inertia-head title="Projects" />
    <main-page>
        <div class="container-fluid p-0 mb-3">
            <div class="row mb-2 gy-3">
                <div class="col-sm-6">
                    <h5 class="d-inline align-middle">Projects</h5>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-end gy-3">
                        <button class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i>
                            <span class="ms-2">Re-Sync</span>
                        </button>
                        <button
                            class="btn btn-primary btn-sm ms-sm-3 ms-md-3 ms-lg-3"
                            @click="openForm()"
                        >
                            <i class="fa fa-plus-circle"></i>
                            <span class="ms-2">Add Website</span>
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
                        <div class="row gy-3 mb-3">
                            <div class="col-12 col-lg-3 col-md-6 col-sm-6">
                                <input
                                    type="text"
                                    id=""
                                    placeholder="Search..."
                                    class="form-control"
                                    v-model="fields.search"
                                    @keyup="chnageMainFilter()"
                                />
                            </div>
                            <div class="col-12 col-lg-3 col-md-6 col-sm-6">
                                <button
                                    class="btn btn-primary"
                                    @click="resetFilter()"
                                >
                                    <i class="fa fa-history"></i>
                                </button>
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
                                    <tbody>
                                        <template v-if="projects.length > 0">
                                            <template
                                                v-for="(
                                                    project, index
                                                ) in projects"
                                                :key="`project_${index}`"
                                            >
                                                <project-row
                                                    :project="project"
                                                ></project-row>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr
                                                style="width: 100%"
                                                class="text-center"
                                            >
                                                <td>
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
                        <div class="row gy-3" v-if="projects.length > 0">
                            <div class="d-flex justify-content-between">
                                <div class="mr-auto">
                                    Showing {{ fields.start_index }} to
                                    {{ fields.end_index }} of
                                    {{ fields.total_record }} Results
                                </div>
                                <div class="mr-auto">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li
                                                class="page-item"
                                                @click="prev()"
                                            >
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

                                            <li
                                                class="page-item"
                                                @click="next()"
                                            >
                                                <span class="page-link"
                                                    ><i
                                                        class="fa fa-angle-double-right"
                                                    ></i
                                                ></span>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-12 col-lg-1 col-md-4 col-sm-4">
                                    <select
                                        name="per_page"
                                        id="per_page"
                                        class="form-select"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <teleport to="body">
            <project-form ref="project_form" @reload="reloadTable" />
        </teleport>
    </main-page>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import ProjectRow from "./ProjectRow.vue";
import axios from "axios";
import { projectRoutes } from "../../routes/ProjectRoutes";
import projectForm from "./Form.vue";

let projects = ref([]);
let loader = ref(true);

let project_form = ref(null);

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

function openForm() {
    project_form.value.openModal();
}

function resetFilter() {
    fields.search = "";
    fields.filter = "latest";
    fields.per_page = 10;
    fields.page = 1;
    reloadTable();
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
        .post(projectRoutes.datatable, fields)
        .then((response) => {
            projects.value = response.data.projects;
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
</script>
