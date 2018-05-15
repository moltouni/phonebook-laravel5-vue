<template>
    <div>
        
        <nav class="navbar fixed-top">
            <router-link :to='{ name: "contact-listing-all" }'>
                <h3 class='navbar-heading'>Phonebook</h3>
            </router-link>
            <button @click="logout" class='btn btn-outline-secondary'>Logout</button>
        </nav>

        <router-view></router-view>

    </div>
</template>

<script>

    export default {
        methods: {
            logout: function () {
                
                let token = document.head.querySelector('meta[name="csrf-token"]');

                axios
                    .post('/logout', { _token: token.content })
                    .then(() => {
                        window.location = '/'
                    });

            }
        }
    }

</script>

<style lang="scss" scoped>
    

</style>
