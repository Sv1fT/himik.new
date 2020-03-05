<template>
    <div class="col-md-9">
        <h4 class="mb-3">Объявления <span class="float-right">
            <router-link to="/user/advert/add" class="btn btn-primary">Добавить объявление</router-link>
        </span></h4>
        <div v-for="advert in adverts.data" class="card mb-3">
            <div class="card-header">{{advert.title}}
                <span class="float-right">
                    <router-link :to="{path: '/user/advert/edit/'+advert.id, params:{ id: advert.id}}" class="btn btn-primary"><i class="fa fa-pencil"></i></router-link>

                    <button class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </span>
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
        <pagination :data="adverts" @pagination-change-page="getResults"></pagination>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                adverts: []
            }
        },
        mounted() {

        axios.get('/api/v1/user/adverts/'+ this.$root.user_id)
            .then(resp => (
                this.adverts = resp.data
            ))
            .catch(function (resp) {
                console.log(resp);
                alert("Could not create your company");
            });

            this.getResults();
        },

        methods: {
            // Our method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('/api/v1/user/adverts/'+ this.$root.user_id +'?page=' + page)
                    .then(response => {
                        this.adverts = response.data;
                    });
            }
        }
    }
</script>
