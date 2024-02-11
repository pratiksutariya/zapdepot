<template>
    <div
        class="modal show d-block"
        id="model-gohilevel"
        aria-hidden="true"
        ref="vuemodal"
        aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1"
    >
        <div class="modal-dialog modal-dialog-centered" style="max-width: 737px">
            <div class="modal-content model-main-body">
                <slot name="modal-logo"></slot>
                <div class="cross-button">
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">{{ modaltitle }}</h5>

                </div>
                <slot name="body"></slot>
            </div>
        </div>
    </div>
    <div class="modal-backdrop"/>
</template>

<script>
import { defineComponent, onMounted, onUnmounted } from "vue";

export default defineComponent({
    name: "Modal",
    emits: ["close"],
    props: {
        modaltitle: String,
    },
    setup() {
        onMounted(() => {
            document.body.classList.add("custom-overflow");
        });
        onUnmounted(() => {
            document.body.classList.remove("custom-overflow");
        });
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
});
</script>

<style scoped>
.modal-backdrop {
    opacity: 0.2;
}
.cross-button{
    position: absolute;
    top: 12px;
    right: 17px;
}
.model-main-body{
    position: relative;
}
</style>
