<script type="text/x-template" id="modal-delete-request">
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 slot="header">Удалить запорс №{{ $route.params.postId }}</h3>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            <div class="col-md-6">
                                <button class="button-accept" v-on:click="deleteRequest">Удалить</button>
                            </div>
                            <div>
                                <router-link to="/">
                                    <button class="button-cancel" @click="$emit('close')">Не удалять</button>
                                </router-link>
                            </div>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>