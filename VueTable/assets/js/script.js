Vue.use(VueTables.ClientTable);

Vue.component( 'app-table', {
    props: [
        'posts',
        'columns',
        'options'
    ],
    template: `
        <div id="table">
            <v-client-table :data="posts" :columns="columns" :options="options">
                <!-- @click="alert('Заголовок: ' + props.row.title + ' Дата: ' + props.row.date + ' Содержание: ' + props.row.content)"-->
                <div slot="child_row" slot-scope="props">
                    <strong>Содержание</strong><br>{{ props.row.content }}
                </div>
                <a slot="uriUpdate" v-bind:posts="posts" slot-scope="props" target="_blank" class="fa fa-pencil-square-o" @click="updatePosts(props.row.id)"></a>
                <a slot="uriDelete" slot-scope="props" target="_blank" class="fa fa-times"></a>
            </v-client-table>
        </div>`,
    methods: {

        // Получаем данные о вызванном посте, после чего по мере надобности изменяем их и возвражаем
        updatePosts: function(id) {
            console.log(id)
            location.href = 'index.html#/' + id;
            return false;   
        }
    }
})

Vue.component( 'show-modal', {
    props: [
        'showModal'
    ],
    template: 
    `<script type="text/x-template" id="modal-template">
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header">
                            <slot name="header">
                                <h3>Новый пост</h3>
                            </slot>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <h3>Заголовок:<input type="text"></h3><br>
                                Автор статьи: <input type="text">
                                Email автора: <input type="email">
                                <h2>Контент:</h2><textarea id="modal-textarea"></textarea>
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                            <button id="close" class="btn btn-danger" @click="$emit('close')">Назад</button>
                            <button id="success" class="btn btn-success" @click="$emit('success')">Опубликовать</button>
                        </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </script>`
})

new Vue({
    el: '#index', 
    components: {
        'modal': modal = { template: '#modal-template' },
    },   
    data: {
        show: true,
        showModal: false,
        endPoint: 'http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_posts',
        columns: [
            'id',
            'title',
            'date',
            'uriUpdate',
            'uriDelete'
        ],
        posts: [],
        options: {
            perPageValues: [5,10,25,50,100],
            perPage: 10,
            headings: {
                title: 'Заголовок',
                date: 'Дата',
                uriUpdate: 'Редактировать',
                uriDelete: 'Удалить'
            },
            sortable: ['id', 'title', 'date']
        }
    },
    created: function() {
        this.getAllPosts()
    },
    methods: {
        // Получаем все посты
        getAllPosts: function() {
            // http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_posts
            this.$http.get(this.endPoint).then(function(response) {  

                this.posts = response.data
                console.log(response)
            })
        }
    }
});