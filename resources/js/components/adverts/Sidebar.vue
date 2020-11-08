<template>
    <div class="row">
        <div class="col-md-3 pt-5">
            <div class="card">
                <div class="card-header"><h4 class="m-0">Фильтр объявлений</h4></div>
                <div class="card-body">
                    <div class="form-group">
                        <p class="mb-2 h5">Категории:</p>
                        <ul class="list-unstyled sidebar_filter_category">
                            <li class="row border-bottom mb-2" v-for="category in categories" v-bind:key="category.id">
                                <span class="col-1">
                                    <input :id="'category_'+category.id" v-on:change.prevent="subcat(category.id)" :disabled="category_ids != category.id && category_ids >= 1"  type="checkbox">
                                    <label :for="'category_'+category.id">{{category.name}}</label>
                                    </span>
                                <span class="col-10"><label :for="'category_'+category.id">{{category.title}}</label></span>
                            </li>
                        </ul>
                    </div>
                    <div class="form-group" v-if="sub_stat != ''">
                        <p class="mb-2 h5">Подкатегории:</p>
                        <ul class="list-unstyled sidebar_filter_category">
                            <li class="row border-bottom mb-2" v-for="subcategory in subcategories" v-bind:key="subcategory.id">
                                <span class="col-1"><input :id="'subcategory_'+subcategory.id" v-on:change.prevent="advert_category(category_ids,subcategory.id)" type="checkbox"></span>
                                <span class="col-10"><label :for="'subcategory_'+subcategory.id">{{subcategory.title}}</label></span>
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <advert-component :adverts_sidebar="adverts"></advert-component>
    </div>

</template>

<script>
import AdvertComponent from './Advert.vue'
    export default {
        components: {AdvertComponent},
        data: function(){
            return {
                adverts: {},
                categories: {},
                subcategories: {},
                sub_stat: '',
                category_ids: ''
            }
        },
        mounted() {
            console.log(this.sub_stat)

        axios.get('/api/v1/adverts')
            .then(resp => (
                this.adverts = resp.data
            ))
            .catch(function (resp) {
                console.log(resp);
                alert("Could not create your company");
            });

        axios.get('/api/v1/categories')
            .then(resp => (
                this.categories = resp.data
            ))
            .catch(function (resp) {
                console.log(resp);
                alert("Could not create your category");
            });
        },

        methods: {
            subcat: function(id) {

                if(this.category_ids == id){
                    this.category_ids = '';
                }else{
                    this.category_ids = id;
                }
                axios.get('/api/v1/categories/'+id)
                .then(resp => (
                    this.subcategories = resp.data.subcategories,
                    this.sub_stat = 1
                ))
                .catch(function (resp) {
                    console.log(resp);
                    this.sub_stat = '';
                    alert("Could not create your category");
                });
            },
            advert_category: function(category_ids,subcat_id){
                axios.get('/api/v1/adverts?category='+category_ids+'&subcategory='+subcat_id)
                        .then(resp => (
                        this.adverts = resp.data
                    ))
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your company");
                    });
            }
        }
    }
</script>
