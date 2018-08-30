Vue.use(VueRouter);

router = new VueRouter({
    props: [
        'posts'
    ],
    routes: [
        { path: '/:id', 
          component: Post = { template: `
            <div>
                <div class="showModal">
                    <transition name="modal">
                        <div class="modal-mask">
                            <div class="modal-wrapper">
                                <div class="modal-container">
                                    <div class="modal-header">
                                        <slot name="header">
                                            <h3>ID поста: {{ $route.params.id }}</h3>
                                        </slot>
                                    </div>
                                    <div class="modal-body">
                                        <slot name="body">
                                        <h3>Заголовок:<input type="text"></h3><br>
                                            Автор статьи: <input type="text" >
                                            Email автора: <input type="email" placeholder="">
                                            <h2>Контент:</h2><textarea id="modal-textarea"></textarea>
                                        </slot>
                                    </div>
                                    <div class="modal-footer">
                                        <slot name="footer">
                                            <a id="close" class="btn btn-danger" href="index.html#/">Назад</a>
                                            <button id="success" class="btn btn-success" @click="$emit('success')">Опубликовать</button>
                                        </slot>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
          ` }
        }
    ]
})
new Vue({
    router
}).$mount('#app')