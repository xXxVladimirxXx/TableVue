<script type="text/x-template" id="modal-edit-request">
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 slot="header">Редактировать запорс №{{ $route.params.postId }}</h3>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            <div class="loading" v-if="loading">Идет загрузка данных. Подождите...</div>
                            <div>
                                <label for="title">Title:</label>
                                <input type="text" id="title" v-model="ThePost.title">
                            </div>
                            <quill-editor v-model="ThePost.content"></quill-editor>
                            <div>
                                <label for="name_company">Название компании:</label>
                                <input type="text" id="name_company" v-model="ThePost.name_company">
                            </div>
                            <div>
                                <label for="count_participant">Количество сотрудников:</label>
                                <input type="number" id="count_participant" v-model="ThePost.count_participant">
                            </div>
                            <div>
                                <label for="type_request">Тип запроса</label>
                                <select v-model="type.term_id" id="type_request">
                                    <option v-for="type_request in type_requests" v-bind:value="type_request.term_id">
                                        {{ type_request.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="field_of_activity">Сфера деятельности</label>
                                <select v-model="ThePost.activity_of_field" id="field_of_activity">
                                    <option v-for="field in field_of_activities" v-bind:value="field">
                                        {{ field }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="site_company">Сайт компании:</label>
                                <input type="text" id="site_company" v-model="ThePost.site_for_company">
                            </div>
                            <div>
                                <label for="selectedKlient">Клиент</label>
                                <select v-model="client.ID" id="selectedKlient">
                                    <option v-for="klient in klients" v-bind:value="klient.data.ID">
                                        {{ klient.data.user_nicename }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="price">Цена:</label>
                                <input type="number" id="price" v-model="ThePost.price">
                            </div>
                            <button name="submit" id="submit_post" class="button-accept" v-on:click="editThisPost">
                                Обновить запрос
                            </button>
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <router-link to="/">
                                <button class="button-cancel" @click="$emit('close')">Вернуться</button>
                            </router-link>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>