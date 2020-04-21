<template>
    <div class="col-md-9">
        <div v-if="success" :class="'alert alert-'+type+' mt-3'">
            {{ message }}
        </div>
        <form @submit.prevent="submit">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{ fields.attributes.company }}</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row" id="update">
                        <div class="col-md-5">
                            <div class="form-group">
                                <image-upload :data-image="getAvatar(fields)" :model-file="fields.attributes.filename"></image-upload>
                            </div>

                        </div>
                        <div class="col-md-7">
                            <form>
                                <div class="form-group row">
                                    <label for="name" class="col-4 col-form-label">ФИО*</label>
                                    <div class="col-8">
                                        <input id="name" name="name" placeholder="ФИО" class="form-control here" required="required" type="text" v-model="fields.attributes.name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company" class="col-4 col-form-label">Организация/Компания*</label>
                                    <div class="col-8">
                                        <input id="company" name="company" placeholder="Название организации" class="form-control here" type="text" v-model="fields.attributes.company">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="newpass" class="col-4 col-form-label">Должность</label>
                                    <div class="col-8">
                                        <input id="newpass" name="newpass" placeholder="New Password" class="form-control here" type="text" v-model="fields.attributes.position">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="select_country" class="col-4 col-form-label">Страна</label>
                                    <div class="col-8">
                                        <input type="text" list="my-list-id" class="form-control here" id="select_country" v-model="fields.attributes.country.name">
                                        <datalist id="my-list-id">
                                            <option>Manual Option</option>
                                            <option v-for="city in cities">{{ city }}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="select_region" class="col-4 col-form-label">Регион</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control here" id="select_region" v-model="fields.attributes.region.name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="select_city" class="col-4 col-form-label">Город</label>
                                    <div class="col-8">
                                        <input type="text" @keypress="changed" class="form-control here" list="city_id" id="select_city" v-model="fields.attributes.city.name">
                                        <datalist id="city_id">
                                            <option>Выберите город</option>
                                            <option v-for="city in cities" :value="city.id" >{{ city.name }}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-4 col-form-label">Адрес</label>
                                    <div class="col-8">
                                        <input id="address" name="address" placeholder="Last Name" class="form-control here" type="text" v-model="fields.attributes.address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="text" class="col-4 col-form-label">Телефон*</label>
                                    <div class="col-8">
                                        <input id="text" name="phone" placeholder="Nick Name" class="form-control here" required="required" type="text" v-model="fields.attributes.number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-4 col-form-label">Email*</label>
                                    <div class="col-8">
                                        <input id="email" name="email" placeholder="Email" class="form-control here" required="required" type="text" v-model="fields.email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="website" class="col-4 col-form-label">Сайт</label>
                                    <div class="col-8">
                                        <input id="website" name="website" placeholder="website" class="form-control here" type="text" v-model="fields.attributes.site">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="publicinfo" class="col-4 col-form-label">Деятельность компании</label>
                                    <div class="col-8">
                                        <textarea id="publicinfo" name="description" cols="40" rows="4" class="form-control" v-model="fields.attributes.description"></textarea>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="form-group w-100 text-center">
                    <input type="submit" class="btn btn-success m-auto" value="Сохранить изменения">
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                profile : [],
                fields : [],
                success: false,
                message: '',
                type: '',
                cities:[]
            }
        },
        async mounted() {
            console.log(this.$root);

            await axios.get('/api/v1/user/profile/'+ this.$root.user_id)
                .then(resp => (
                    this.fields = resp.data
                ))
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not create your company");
                });
        },
        methods: {
            getAvatar(avatar) {
                console.log(avatar);
                return avatar.attributes.filename;
            },
            submit() {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                this.errors = {};
                if (typeof this.$children[0].imageSrc != "undefined"){
                    this.fields.attributes.filename = this.$children[0].imageSrc;
                }
                axios.post('/api/v1/user/profile/save/'+ this.$root.user_id, this.fields).then(response => {
                    //this.success = true;
                    this.$toast.open({message: response.data.message, type: response.data.type})
                }).catch(error => {
                    if (error.response.type === 'error') {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            changed() {
                console.log(this.fields.attributes.city.name);

                axios.post('/api/v1/city/'+ this.fields.attributes.city.name).then(response => {
                    this.cities = response.data;
                    //this.success = true;
                    //this.$toast.open({message: response.data.message, type: response.data.type})
                }).catch(error => {
                    if (error.response.type === 'error') {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
        }
    }
</script>
