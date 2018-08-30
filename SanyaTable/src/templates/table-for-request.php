<?php
wp_enqueue_script('table-request');

$field_name = "field_5a7ad2f25c7cd";
$activity_of_field = get_field_object($field_name);

$args = array(
	'taxonomy' => 'type_inquiries',
	'hide_empty' => false,
);
$terms = get_terms($args);

$klients = get_users();
$data_for_select = [
    'klients'  => $klients,
    'nonce'    => wp_create_nonce('wp_rest'),
    'ajaxurl'  => admin_url('admin-ajax.php'),
    'postType' => 'inquiries',
		'terms'    => $terms,
		'activity_of_field' => $activity_of_field['choices'],
];
wp_localize_script('table-request', 'data_select', $data_for_select);

include('modal-delete-request.php');
include('modal-edit-request.php');
?>

<div id="requests_table" class="box box-solid" v-cloak>
	<div class="box-body no-padding">

		<div id="chart_div" style="width: 100%; height: 500px;"></div>

    <!-- app -->
    <div id="app">
        <router-link to="/sozdat-zapros/">
            <div id="new_request">
                <button id="show-modal">Создать запрос</button>
            </div>
        </router-link>
    </div>

    <router-view v-on:create-post="fetchItems" v-on:delete-post="fetchItems"></router-view>

    <div id="totalSum">Общая сумма: {{ count }}$</div>

    <div class="loading" v-if="loading">
        Происходит загрузка данных, пожалйуста, подождите секундочку... Или больше
    </div>

    <table class="table table-bordered table-striped" v-if="paginatedProcessedItems">
        <tr>
            <td>
                <input type="number" class="form-control input-sm" v-on:keyup="filterBy('#', $event.target.value)">
            </td>
            <td>
                <input type="text" class="form-control input-sm" v-on:keyup="filterBy('Дата', $event.target.value)">
            </td>
            <td>
                <input type="text" class="form-control input-sm" v-on:keyup="filterBy('Клиент', $event.target.value)">
            </td>
            <td>
                <input type="text" class="form-control input-sm" v-on:keyup="filterBy('Тип запроса', $event.target.value)">
            </td>
            <td>
                <input type="text" class="form-control input-sm" v-on:keyup="filterBy('Название', $event.target.value)">
            </td>
            <td>
                <input type="number" class="form-control input-sm" v-on:keyup="filterBy('Цена', $event.target.value)">
            </td>
            <td>
                <input type="text" class="form-control input-sm" v-on:keyup="filterBy('Статус', $event.target.value)">
            </td>
        </tr>
        <tr>
            <th v-for="column in columns" @click="sortBy(column)" :class="{active: sortColumn == column, clickable: sortableColumns.indexOf(column) > -1}">
                <i class="fa" :class="getSortIconClass(column)"></i>{{ column }}
            </th>
        </tr>
        <tr v-for="item in paginatedProcessedItems">
            <td><router-link :to="{ name: 'inquiries', params: { postId: item.id }}">{{ item.id }}</router-link></td>
            <td>{{ item.date }}</td>
            <td>{{ item.client.display_name }}</td>
            <td>{{ item.type }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.price }}$</td>
            <td>{{ item.status }}</td>
            <td>
                <router-link :to="{ name: 'edit', params: { postId: item.id }}">
                    <i class="fa fa-edit" title="Редактировать"></i>
                </router-link>
            </td>
            <td>
                <router-link :to="{ name: 'delete', params: { postId: item.id }}">
                    <i class="fa fa-trash" title="Удалить"></i>
                </router-link>
            </td>
        </tr>
    </table>
		
		<div v-if="(processedItems.length <= 0)" class="box-body text-center">Нет элементов таблицы.</div>
	</div>
	
	<div class="box-body" v-if="isPaginated()">
		<ul class="pagination no-margin pull-right">
			
			<li><a @click.prevent="paginate(false)" href="#"><i class="fa fa-arrow-circle-left"></i></a></li>
			
			<li v-for="n in Math.ceil(processedItems.length / perPage)" :class="{active: page == n}">
				<a @click.prevent="paginate(n)" href="#">{{n}}</a>
			</li>
			
			<li><a @click.prevent="paginate(true)" href="#"><i class="fa fa-arrow-circle-right"></i></a></li>
		
		</ul>
	</div>

</div>
