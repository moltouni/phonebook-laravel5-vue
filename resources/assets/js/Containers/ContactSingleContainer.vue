<template>

    <div class='container'>
        
        <div class="contact-container" v-if="contact">
            <div class="row">
                <div class="col-4 contact-left">

                    <avatar :username='contact.first_name + contact.last_name' :src='contact.avatar' :size='256' class='contact-avatar'></avatar>

                    <button @click='favoriteToggle()' class="favorite contact-edit-button btn btn-outline-success">
                        <icon v-if="!contact.favorite" class='icon' name='regular/heart'></icon>
                        <icon v-else class='icon' name='heart'></icon>                        
                        Favorite
                    </button>

                    <router-link :to='{ name: "contact-single-edit" }'>
                        <button class="contact-edit-button btn btn-outline-secondary">Edit</button>
                    </router-link>

                    <button @click='destroy()' class="contact-edit-button btn btn-outline-danger">Delete</button>

                </div>
                <div class="col contact-right">
                    
                    <div class='contact-info'>

                        <div class='contact-info-name'>
                            <h1>{{ contact.first_name }} {{ contact.last_name }}</h1>
                        </div>

                        <div class='contact-info-email'>

                            <a :href="'mailto:' + contact.email">{{ contact.email }}</a>

                        </div>

                    </div>

                    <div class='contact-phones' v-if="phones">
                        
                        <div class='contact-phones-item' v-for="phone in phones" :key="phone.id">
                            
                            <h3 class='contact-phones-item-label'>{{phone.label}}</h3>
                            <a :href="'tel:' + phone.number" class='contact-phones-item-number'><icon name='phone'></icon> {{phone.number}}</a>

                        </div>

                        <div v-if="!phones.length">
                            <span>Nothing here...</span>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script>

    import Avatar from 'vue-avatar'

    export default {

        components: {
            Avatar
        },

        mounted() {
            this.contact_id = this.$route.params.contact;
            this.getContact()
            this.getPhones()
        },             
        data() {
            return {
                contact: null,
                phones: null,
                contact_id: null,
            }
        },
        methods: {
        
            getContact: function () {

                let url = `/api/contacts/${this.contact_id}`
                axios
                    .get(url)
                    .then( (response) => {
                        this.contact = response.data.data
                    });

            },

            getPhones: function() {

                let url = `/api/contacts/${this.contact_id}/phones`
                axios
                    .get(url)
                    .then( (response) => {
                        this.phones = response.data.data
                    });

            },

            destroy: function() {

                const url = `/api/contacts/${this.contact.id}`
                axios
                    .delete(url)
                    .then( (response) => {
                        this.$router.push({ name: 'contact-listing-all' })
                    });

            },
            favoriteToggle: function() {

                let favorite_new = this.contact.favorite ? 0 : 1
                const url = `/api/contacts/${this.contact.id}`
                let data = new FormData()
                data.append('_method', 'PATCH')
                data.append('favorite', favorite_new)

                axios
                .post(url, data)
                .then( (response) => {
                    this.getContact()
                })

            },

        },   
    }
</script>

<style lang="scss" scoped>

    $margin: 64px;
    $favorite-color: #22863a;

    .contact-container {
        
        margin: $margin;

        .contact-left {

            text-align: center;

            .contact-avatar {
                display: inline-block;
            }

            .favorite {
                .icon {
                    padding-right: 8px;
                }
            }

            .contact-edit-button {
                margin-top: $margin;
            }

        }

        .contact-info {

            .contact-info-name {
                margin-top: $margin / 8;
                font-weight: bold;
            }            

            .contact-info-email {
                font-size: 24px;
            }
        }

        .contact-phones {

            .contact-phones-item {

                margin-top: $margin;

                .contact-phones-item-label {
                    font-weight: bold;
                }
                .contact-phones-item-number {
                    font-size: 24px;
                }

            }
        }

    }
</style>
