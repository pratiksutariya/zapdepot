<template>
    <!-- <vue-element-loading :active="active" :is-full-screen="true" background-color="rgba(255, 255, 255, 97%)" color="#00008B" size="50"/> -->
    <loading
        :active="acitve"
        color="#007BFF"
        :width="width"
        :height="height"
        :can-cancel="true"
        :is-full-page="true"
    />
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
export default {
    components: {
        Loading,
    },
	data(){
		return {
			width:36,
			height:36
		}
	},
    props: ["acitve"],
};
</script>
