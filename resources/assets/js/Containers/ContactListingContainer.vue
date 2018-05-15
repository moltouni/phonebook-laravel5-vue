<template>

    <div class="contact-listing col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <GlobalEvents
        @keyup.191="focusSearch"
        />

        <div class='filter'>

            <router-link :to='{ name: "contact-listing-all" }'>
                <div v-bind:class="{ active: filter == 'all'}" class="filter-box">
                    <h3 class="filter-box-text">All</h3>
                </div>                        
            </router-link>

            <router-link :to='{ name: "contact-listing-favorites" }'>
                <div v-bind:class="{ active: filter == 'favorites'}" class="filter-box">
                    <h3 class="filter-box-text">Favorites</h3>
                </div>
            </router-link>

        </div>

        <div class='search'>
            <input type='text' ref='search' @input='search' v-model="query" placeholder="Search" />
        </div>
        
        <pulse-loader class='loader' :loading="loading" size="16px"></pulse-loader>

        <div v-if='!loading' class='contacts'>    

            <router-link v-if="filter != 'search'" class='contact-add' :to='{ name: "contact-add" }'>
                <icon name='plus'></icon>
            </router-link>

            <contact-component @favoriteToggle="favoriteToggle" @destroy="destroy" v-for="contact in contacts" :key="contact.id" :contact="contact"></contact-component>

            <div v-if="filter == 'search' && !contacts.length">
                <h3>You've lost me...</h3>
            </div>

        </div>

    </div>

</template>

<script>

    import _ from 'lodash';
    import GlobalEvents from 'vue-global-events'
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
    import ContactComponent from '../Components/ContactComponent'
	
    export default {
        components: {
            PulseLoader,
            GlobalEvents,
            ContactComponent,
        },
        created() {
            
            let routeName = this.$router.currentRoute.name;
            if (routeName == 'contact-listing-favorites') {
                this.filter = 'favorites'
                this.apiUrl = '/api/contacts?search=favorite:1'
            }
            else if (routeName == 'contact-listing-all') {
                this.filter = 'all'
                this.apiUrl = '/api/contacts'
            }
            else if (routeName == 'contact-listing-search') {

                this.query = this.$route.query.q;
                if (this.query == '') {
                    this.$router.push({ name: 'contact-listing-all' })
                }

                this.filter = 'search'
                this.apiUrl = `/api/contacts?searchFields=first_name:like;last_name:like;email:like&search=${this.query}`
            }
            
        },
        mounted() {
            this.$refs.search.focus();
            this.getContacts()
        },             
        data() {
            return {
                query: null,
                contacts: [],
                loading: true,

                apiUrl: null,
                filter: null,
            }
        },
        methods: {

            focusSearch: function() {
                this.$refs.search.focus();
            },

            search: _.debounce(function (e) {
                
                this.$router.push({ name: 'contact-listing-search', query: { q: this.query }})

            }, 200),

            getContacts: function () {

                this.loading = true

                axios
                    .get(this.apiUrl)
                    .then( (response) => {
                        this.contacts = response.data.data
                        this.loading = false
                    });

            },

            favoriteToggle: function(contact) {
                
                let favorite_new = contact.favorite ? 0 : 1
                const url = `/api/contacts/${contact.id}`
                let data = new FormData()
                data.append('_method', 'PATCH')
                data.append('favorite', favorite_new)

                axios
                .post(url, data)
                .then( (response) => {
                    this.getContacts()
                })

            },
            destroy: function(contact) {

                const url = `/api/contacts/${contact.id}`
                axios
                    .delete(url)
                    .then( (response) => {
                        this.getContacts()
                    });

            }

        },   
    }
</script>

<style lang="scss" scoped>
    
    $margin: 64px;
    $filter-box-bg: #fff;
    $filter-box-icon-size: 24px;

    $contact-margin: 64px;
    $contact-width: 420px;
    $contact-height: $contact-width/1.7;

    $search-font-size: 24px;

    .contact-listing {

        .filter, .contacts {
            margin-top: $margin;
        }

        .filter {

            margin: $margin auto;
            text-align: center;

            .filter-box {

                font-weight: bold;
                text-align: center;
                display: inline-block;
                margin: 0 $margin;

                &.active {
                    border-bottom: 2px solid #666;
                }
                
                .filter-box-icon {
                    font-size: $filter-box-icon-size;
                }

                .filter-box-text {
                    font-weight: bold;
                    margin-top: $margin/8;
                }

            }
        }

        .search {
            text-align: center;
            input {
                width: 600px;
                font-size: $search-font-size;
                padding: 8px 16px;
                &:focus {
                    outline: none;
                }
            }
        }

        .loader {
            text-align: center;
            margin: $margin;
        }

        .contacts {
            text-align: center;
            .contact-add {
                display: inline-block;
                margin: $margin;
                overflow: hidden;
                text-align: center;
                background: #eee;
                border: 3px dashed #ddd;
                border-radius: 8px;
                padding: 80px;
                width: $contact-width;
                height: $contact-height;          
                font-size: 64px;      
            }
        }
    }
</style>
