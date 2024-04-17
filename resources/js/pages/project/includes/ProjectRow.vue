<template>
    <tr style="background-color: rgb(248 248 248)">
        <td style="min-width: 50px; text-align: center">
            <i
                class="text-success fa fa-arrow-up"
                v-if="project.up_or_down === 1"
            ></i>
            <i
                class="text-danger fa fa-arrow-down"
                v-if="project.up_or_down === 0"
            ></i>
            <div>
                <small>{{ project.up_or_down === 1 ? "Up" : "Down" }}</small>
            </div>
        </td>
        <td style="min-width: 50px">
            <img
                :src="`/storage/${project.project_logo_path}`"
                class="rounded"
                style="height: 35px; width: auto"
                alt="profile image"
            />
        </td>
        <td style="min-width: 200px">
            <h6>
                {{ project.project_name }}
            </h6>
            <a
                :href="project.project_url"
                style="text-decoration: none"
                target="_blank"
                class="mt-1"
                ><i class="fa fa-external-link fs-8"></i
                ><small class="text-primary ms-1">{{
                    project.project_url
                }}</small></a
            >
        </td>
        <td style="min-width: 10px">
            <div
                style="width: 1%; border-left: 2px solid #e8e8e8; height: 51px"
            ></div>
        </td>
        <td style="min-width: 150px">
            <div>
                <img
                    src="../../../../../../../images/google.png"
                    style="height: 15px; width: auto"
                    alt="profile image"
                />
                <small class="ms-2">{{ project.google_rank }} / 100</small>
            </div>
            <div>
                <i class="fa fa-tachometer text-danger fs-8"></i>
                <small class="ms-2">{{ project.time }} ms</small>
            </div>
        </td>
        <td style="min-width: 150px">
            <span
                class="badge rounded-pill p-2"
                style="background-color: rgb(255 213 213)"
            >
                <i class="fa fa-cog fs-6 text-danger"></i
                ><span class="ms-2 text-dark"
                    >{{ project.total_update }} Updates</span
                >
            </span>
        </td>
        <td style="min-width: 150px">
            <i class="fa fa-history text-success fs-5"></i>
            <small class="ms-2">Backup active</small>
        </td>
        <td style="min-width: 150px">
            <i class="fa fa-heart text-success fs-5"></i>
            <small class="ms-2"
                >{{ project.total_site_helth }} Site helth</small
            >
        </td>
        <td style="min-width: 150px">
            <img
                src="../../../../../../../images/php.png"
                style="height: 17px; width: auto"
                alt="profile image"
            />
            <small class="ms-2">{{ project.total_php_issue }} Issue</small>
        </td>
        <td style="min-width: 150px">
            <a
                :href="project.wp_admin_url"
                target="_blank"
                class="btn btn-light bg-white border"
            >
                <img
                    src="../../../../../../../images/wordpress.png"
                    class="rounded"
                    style="height: 20px; width: auto"
                    alt="wp"
                />
                <small class="ms-2">WP Admin</small>
            </a>
        </td>
    </tr>
</template>

<script setup>
const props = defineProps({
    project: {
        type: Object,
        required: true,
        default: {},
    },
});
</script>
