<template>
    <div class="col-md-9">
        <div class="pb-4">
            <a class="text-blue h5 w-100 pr-3" href="#" v-on:click.prevent="type('')" >Все объявления</a>
            <a class="text-blue h5 w-100 pr-3" href="#" v-on:click.prevent="type('0')" >Куплю</a>
            <a class="text-blue h5 w-100" href="#" v-on:click.prevent="type('1')">Продам</a>
        </div>
        <div v-for="advert in adverts_sidebar.data" class="card mb-3">
            <div class="card-header">
                <h2 class="h4 m-0"><a class="text-blue" :href="'/advert/show/'+advert.slug">{{advert.title}}</a></h2>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <img :src="/storage/+advert.filename" class="img-fluid" alt="">
                    </div>
                    <div class="col-9">
                        <p>{{ advert.content.substr(0,200) }}</p>
                        <p>Добалено: {{ advert.created_at | formatDate }}</p>
                        <p>Цена: {{ advert.types.price }} {{ advert.types.valute }}</p>
                        <p>Статус: {{ advert.statuses.name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <pagination :data="adverts_sidebar" :limit="15" @pagination-change-page="getResults"></pagination>
    </div>
</template>

<script>
    export default {

        props: {
            adverts_sidebar :{},
            category_id:'',
            subcategory_id:''
        },
        methods: {
            // Our method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('/api/v1/adverts?page=' + page)
                    .then(response => {
                        this.adverts = response;
                    });
            },
            type: function(id) {
                if(id == ''){
                    axios.get('/api/v1/adverts')
                        .then(resp => (
                        this.adverts_sidebar = resp.data
                    ))
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your company");
                    });
                }else{
                    axios.get('/api/v1/adverts?type='+id)
                        .then(resp => (
                        this.adverts_sidebar = resp.data
                    ))
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your company");
                    });
                }
            }
        }
    }
</script>
