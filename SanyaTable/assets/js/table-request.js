var tableVue;
var klients = data_select.klients;
var postType = data_select.postType;
var field_of_activities = data_select.activity_of_field;
var type_requests = data_select.terms;
var ajaxurl = data_select.ajaxurl;

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart(items) {

    var items = items;
    items.reverse();

    var year = [];
    var Many = 0;
    var price = [];
    var resultValues = [];

    if(items !== undefined) {

        for(var i = 0; i < items.length; i++) {

            Many = Many + parseInt(items[i].price);

            year[i]  = items[i].date;
            price[i] = Many;

            resultValues.push([year[i], price[i]]);
        }
    }

    resultValues.unshift(['year', 'price']);

    var data = google.visualization.arrayToDataTable(
        resultValues
    );

    var options = {
        title: 'Payments history',
        hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0}
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}

/**
 * Возвращает объект с массивов ключей и массивом названий столбцов
 *
 * @returns {{keys: [], values: []}}
 */
function getColumns() {

  var columns = {
    id: '#',
    date: 'дата',
    client: 'Клиент',
    type: 'Тип запроса',
    title: 'Название',
    price: 'Цена',
    status: 'Статус',
  };

  return {
    keys: Object.keys(columns),
    values: Object.values(columns),
  };
}

/**
 * Возвращает массив колонок доступных для сортировки
 *
 * @returns {[]}
 */
function getSortableColumns() {

  return [
    '#',
    'дата',
    'Тип запроса',
    'Название',
    'Цена',
    'Статус',
  ];
}

Vue.use(VueQuillEditor)

var edit_request = Vue.component('v-edit', {
    data: function() {
        return {
            loading: false,
            klients: klients,
            ThePost: {},
            post_type: postType,
            client: {},
            type: {},
            editPost: ajaxurl + '?action=edit_request',
            routeGetThePost: ajaxurl + '?action=get_the_post',
            field_of_activities: field_of_activities,
            type_requests: type_requests,
            field: {},
            klient: {
                data: {
                    ID: ''
                }
            },
        }
    },
    created: function() {
        this.getThePost();
    },
    methods: {
        getThePost: function() {

            this.loading = true;

            this.$http.post(this.routeGetThePost, { postId: this.$route.params.postId }).then(function(response) {

                this.ThePost = response.data[0];

                this.client = this.ThePost.client;
                this.type   = this.ThePost.type;

                this.loading = false;
            });
        },

        editThisPost: function() {
            this.loading = true;

            var dataForUpdate = {
                'Post': this.ThePost,
                'client': this.client,
                'post_type': this.post_type
            };

            this.$http.post(this.editPost, { formData: dataForUpdate }).then(function(response) {

                console.log(response);
                if(response.status === 200) {

                    this.$emit('create-post');
                }

                this.loading = false;
            });
        }
    },
    template: '#modal-edit-request'
})

var delete_request = Vue.component('v-delete', {
    data: function() {
        return {
            deleteAjaxUrl: ajaxurl + '?action=delete_request',
        }
    },
    methods: {
        deleteRequest: function() {

            this.loading = true;

            this.$http.post(this.deleteAjaxUrl, { postId: this.$route.params.postId }).then(function(response) {

                console.log(response);
                if(response.status === 200) {

                    this.$emit('delete-post');
                }
                this.loading = false;
            });
        }
    },
    template: '#modal-delete-request'
})

var form = Vue.component('v-form', {
    data: function(){
        return {
            klients: klients,
            form: {
                title: '',
                content: '',
                name_company: '',
                count_participant: 0,
                field_of_activity: '',
                site_company: '',
                selectedKlient: '',
                price: 0,
                postType: postType,
                type_request: '',
            },
            createPost: ajaxurl + '?action=create_post',
            field_of_activities: field_of_activities,
            type_requests: type_requests,
        }
    },
    http: {
        root: ajax_data.url,
    },
    components: {
        LocalQuillEditor: VueQuillEditor.quillEditor
    },
    methods: {
        createNewPost: function() {

            this.$http.post(this.createPost, { form: this.form }).then(function(response) {

                if(response.status === 200) {

                    this.$emit('create-post')
                }
            })
        },
    },
    template:
    '<transition name="modal">\n' +
    '\t\t<div class="modal-mask">\n' +
    '\t\t\t<div class="modal-wrapper">\n' +
    '\t\t\t\t<div class="modal-container">\n' +
    '\n' +
    '\t\t\t\t\t<div class="modal-header">\n' +
    '\t\t\t\t\t\t<h3 slot="header">Новый запрос</h3>\n' +
    '\t\t\t\t\t</div>\n' +
    '\n' +
    '\t\t\t\t\t<div class="modal-body">\n' +
    '<slot name="body">' +
    '   <div>' +
    '      <label for="title">Title:</label>' +
    '      <input type="text" id="title" v-model="form.title">' +
    '   </div>' +
    '   <quill-editor v-model="form.content"></quill-editor>' +
    '   <div>' +
    '      <label for="name_company">Название компании:</label>' +
    '      <input type="text" id="name_company" v-model="form.name_company">' +
    '   </div>' +
    '   <div>' +
    '      <label for="count_participant">Количество сотрудников:</label>' +
    '      <input type="number" id="count_participant" v-model="form.count_participant">' +
    '   </div>' +
    '   <div>' +
    '      <label for="type_request">Тип запроса</label>' +
    '      <select v-model="form.type_request" id="type_request">' +
    '           <option v-for="type_request in type_requests" v-bind:value="type_request.term_id">' +
    '              {{ type_request.name }}' +
    '           </option>' +
    '      </select>' +
    '   </div>' +
    '   <div>' +
    '      <label for="field_of_activity">Сфера деятельности</label>' +
    '      <select v-model="form.field_of_activity" id="field_of_activity">' +
    '           <option v-for="field in field_of_activities" v-bind:value="field">' +
    '               {{ field }}' +
    '           </option>' +
    '      </select>' +
    '   </div>' +
    '   <div>' +
    '      <label for="site_company">Сайт компании:</label>' +
    '      <input type="text" id="site_company" v-model="form.site_company">' +
    '   </div>' +
    '   <div>' +
    '      <label for="selectedKlient">Клиент</label>' +
    '      <select v-model="form.selectedKlient" id="selectedKlient">' +
    '           <option v-for="klient in klients" v-bind:value="klient.data.ID">{{ klient.data.user_nicename }}</option>' +
    '      </select>' +
    '   </div>' +
    '   <div>' +
    '      <label for="price">Цена:</label>' +
    '      <input type="number" id="price" v-model="form.price">' +
    '   </div>' +
    '<button name="submit" id="submit_post" class="button-accept" v-on:click="createNewPost">Создать заказ</button>' +
    '</slot>\n' +
    '\t\t\t\t\t</div>\n' +
    '\n' +
    '<div class="modal-footer">\n' +
    '\t<slot name="footer">\n' +
    '\t\t<router-link to="/">\n' +
    '\t\t\t<div id="close_new_request">\n' +
    '\t\t\t\t\<button @click="$emit(\'close\')">\n' +
    '\t\t\t\t\tЗакрыть\n' +
    '\t\t\t\t</button>\n' +
    '\t\t\t</div>\n' +
    '\t\t</router-link>\n' +
    '\t</slot>\n' +
    '</div>\n' +
    '\n' +
    '\t\t\t\t</div>\n' +
    '\t\t\t</div>\n' +
    '\t\t</div>\n' +
    '\t</transition>'
})

// Компонент, рисующий один пост
var request = Vue.component('request', {
    data: function() {
      return {
        ThePost: {},
        client: {},
        typeRequest: {},
        loading: false,
        routeGetThePost: ajaxurl + '?action=get_the_post',
      }
    },
    http: {
        root: ajax_data.url,
    },
    created: function () {

        this.getThePost();
    },
    methods: {
        getThePost: function() {

            this.loading = true;

            this.$http.post(this.routeGetThePost, { postId: this.$route.params.postId }).then(function(response) {

                console.log(response);
                this.ThePost = response.data[0];
                this.client = this.ThePost.client;
                this.typeRequest = this.ThePost.type;

                this.loading = false;
            });
        },
    },
    template:
    '<transition name="modal">\n' +
    '<div class="modal-mask">\n' +
    '<div class="modal-wrapper">\n' +
    '<div class="modal-container">\n' +
    '<div class="modal-header">\n' +
    '<h3>{{ ThePost.title }}</h3>\n' +
    '</div>\n' +
    '<div class="modal-body">\n' +
    '<slot name="body">\n' +
    '<div class="post-content">' +
    '<div id="information_about_request">' +
    '<div class="loading" v-if="loading">Идет загрузка данных. Подождите...</div>' +
    '<h2>Информация о запросе</h2>' +
    '<p><strong>Тип запроса: </strong>{{ typeRequest.name }}</p>' +
    '<p><strong>Название компании: </strong>{{ ThePost.name_company }}</p>' +
    '<p><strong>Количество сотрудников компании: </strong>{{ ThePost.count_participant }}</p>' +
    '<p><strong>Сфера деятельности компании: </strong>{{ ThePost.activity_of_field }}</p>' +
    '<p><strong>Сайт компании: </strong>{{ ThePost.site_for_company }}</p>' +
    '<p><strong>Клиент: </strong>{{ client.display_name }}</p>' +
    '<p><strong>Статус запроса: </strong>{{ ThePost.status }}</p>' +
    '</div>' +
    '<div class="clear"></div>' +
    '</div>' +
    '</slot>\n' +
    '</div>\n' +
    '<div class="modal-footer">\n' +
    '<slot name="footer">\n' +
    '<router-link to="/">\n' +
    '<div id="close_new_request">\n' +
    '<button @click="$emit(\'close\')">\n' +
    'Закрыть' +
    '</button>\n' +
    '</div>\n' +
    '</router-link>\n' +
    '</slot>\n' +
    '</div>\n' +
    '</div>\n' +
    '</div>\n' +
    '</div>\n' +
    '</transition>'
})

var router = new VueRouter({
    routes: [
        {
            path: '/sozdat-zapros/',
            component: form
        },
        {
            path: '/inquiries/:postId',
            name: 'inquiries',
            component: request,
        },
        {
            path: '/delete/:postId',
            name: 'delete',
            component: delete_request,
        },
        {
            path: '/edit/:postId',
            name: 'edit',
            component: edit_request
        },
    ],
});

/**
 * Запуск Vue.js приложения списка Предложений
 */
window.onload = function() {

  /**
   * Конфигурация Vue компонента таблицы
   *
   * @type {{}}
   */
tableVue = new Vue({
    router: router,
    el: '#requests_table',
    data: function() {

      var sortableColumns = getSortableColumns();
      var sortOrders = {};

      // Установка порядка сортировки по-умолчанию для сортируемых колонок
      sortableColumns.forEach(function(column) {

      sortOrders[column] = 1;
      });

      return {
        showModal: false,
        count: 0,
        columns: getColumns().values,
        loading: false,
        items: [],
        processedItems: [],

        perPage: 5,
        page: 1,

        sortColumn: '',
        sortableColumns: sortableColumns,
        sortOrders: sortOrders,

        filters: {},

        routeGetPosts: ajaxurl + '?action=get_all_posts',
      };
    },

    /**
     * Адрес для Vue Router
     */
    http: {
      root: ajax_data.url,
    },

    /**
     * Событие создания
     */
    created: function() {

      this.fetchItems();
    },

    methods: {
      /**
       * Вычисление класса font-awesome иконки идентифицирующей сортировку
       *
       * @param {string} key
       * @returns {string}
       */
      getSortIconClass: function(key) {

        if (typeof this.sortOrders[key] !== 'undefined') {

          return this.sortOrders[key] > 0 ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc';
        }
      },

      /**
       * Сортирует по колонке
       *
       * @param {String} column
       * @param {bool|String} [order] Порядок сортировки true - ASC, false - DESC.
       *                      Если не указан и это повторная сортировка по этому ключу порядок инвертируется, если первая то ASC.
       *                      Если указать пустую строку '' то сохранится существующий порядок.
       */
      sortBy: function(column, order) {

        // Определение порядка сортировки
        if (this.sortableColumns.indexOf(column) > -1) {

          if (typeof order !== 'undefined') {

            this.sortOrders[column] = order === '' ? this.sortOrders[column] : order ? 1 : -1;

          } else {

            this.sortOrders[column] = (this.sortColumn === column) ? this.sortOrders[column] * -1 : 1;
          }

          this.sortColumn = column;
        }

        var sortObjectKey = getColumns().keys[this.columns.indexOf(this.sortColumn)];
        var sortOrder = this.sortOrders[this.sortColumn] || 1;

        if (sortObjectKey && this.processedItems instanceof Array) {
          this.processedItems = this.processedItems.slice().sort(function(a, b) {

            a = a[sortObjectKey];
            b = b[sortObjectKey];

            return (a === b ? 0 : a > b ? 1 : -1) * sortOrder;
          });
        }
      },

      /**
       * Возвращает дату по полной строке даты и времени
       *
       * @param {String} fullDate
       * @param {String} [divider=-]
       *
       * @return {String}
       */
      getDate: function(fullDate, divider) {

        if (typeof divider === 'undefined') {

          divider = '.';
        }

        var
          date = new Date(fullDate),
          day = ('0' + date.getDate()).slice(-2),
          month = ('0' + (date.getMonth() + 1)).slice(-2),
          year = date.getFullYear();

        return day + divider + month + divider + year;
      },

      /**
       * Изменяет страницу пагинации
       *
       * @param {Number} page
       */
      paginate: function(page) {

        this.page = (typeof page === 'number') ? page : this.page;
        this.page = (page === true && this.page < Math.ceil(this.processedItems.length / this.perPage)) ? this.page + 1 : this.page;
        this.page = (page === false && this.page > 1) ? this.page - 1 : this.page;
      },

      /**
       * Определяет необходимость пагинации
       *
       * @return {boolean}
       */
      isPaginated: function() {

        return (this.processedItems.length > 0 && this.processedItems.length > this.perPage);
      },

      /**
       * Добавляет фильтр по колонке
       * Фильтрует по всем добавленным фильтрам
       *
       * @param {String} column
       * @param {String} value
       */
      filterBy: function(column, value) {

          if (typeof column === 'undefined' || value === 'undefined' || this.items.length < 0) {

              return;
          }

          this.processedItems = this.items;
          this.filters[column] = value;
          var vm = this;

          // По диапазону дат
          if (typeof this.filters['Дата'] !== 'undefined' && this.filters['Дата'] !== '') {

              var getData = this.filters['Дата'];

              getData = getData.split('.');

              if(getData.length >= 2) {

                  var fullData = this.getDate(getData);
                  var split = fullData.split('.');

                  this.processedItems = this.processedItems.filter(function(model) {

                      var allDatesSplit = model.date.split('.');

                      if((allDatesSplit[2] === split[2] && allDatesSplit[1] === split[1])) {

                          return model;
                      }
                  });
              }
          }

          // По обычным значениям
          if (column !== 'Дата') {
              if (getColumns().values.indexOf(column) > -1) {

                  var filterObjectKey = getColumns().keys[this.columns.indexOf(column)];
                  var filterValue     = vm.filters[column];

                  if(filterValue !== '') {

                      this.processedItems = this.processedItems.filter(function(model) {

                          var orderValue = model[filterObjectKey];

                          var stringOrderValue = String(orderValue); // Для поиска по id

                          if(filterObjectKey !== 'id' &&
                              (stringOrderValue === filterValue || stringOrderValue.toLowerCase().search(filterValue.toLowerCase()) > -1)) {

                              return model;
                          } else if(filterObjectKey === 'id' && stringOrderValue.indexOf(filterValue) > -1) {

                              return model;
                          } else if(filterObjectKey == 'client' && orderValue.display_name.toLowerCase().indexOf(filterValue.toLowerCase()) > -1) {

                              return model;
                          }
                      });
                  }
              }
          }

          // Общая сумма всех заказов
          this.count = 0;
          this.totalSum = this.processedItems;
          for (var i = 0; i < this.totalSum.length; i++) {

              this.count = +this.count + +this.totalSum[i]['price'];
          }
          // Пересортировка в том же порядке после фильтрации
          this.sortBy(this.sortColumn, '');
      },

      /**
       * Получаеь элементы по API
       */
      fetchItems: function() {

        this.loading = true;
        this.$http.get(this.routeGetPosts).then(function(response) {

          this.items = response.body;
          this.processedItems = this.items;

          drawChart(this.processedItems);

          // Общая сумма всех заказов
          this.count = 0;
          this.totalSum = this.items;
          for (var i = 0; i < this.totalSum.length; i++) {

            this.count = +this.count + +this.totalSum[i]['price'];
          }

          this.loading = false;
        });
      },
    },

    computed: {
      /**
       * Возвращает обработанные элементы применяя пагинацию
       * Не изменяет массив this.processedItems для для адекватной установки необходимости пагинации
       * с учётом изменения количества элементов после фильтрации
       *
       * @return {*}
       */
      paginatedProcessedItems: function() {

      this.sortBy('Дата начала', false);

        // Пагинация без изменения исходного массива
        if (this.processedItems.length > this.perPage) {

          var start = (this.page === 1) ? 0 : (this.page - 1) * this.perPage;
          var end = start + this.perPage;

          var padinatedItems = this.processedItems.slice(start, end);
        }

        // Генерация события изменения данных для внешних наблюдателей
        var event = new CustomEvent('adminlte_table_data_processed', {detail: this.items});
        document.dispatchEvent(event);

        return padinatedItems || this.processedItems;
      },
    },
  });
};
