<template>
    <div class="contact">

        <div class='controls'>
            <div @click='favoriteToggle()' class='favorite'>
                <icon v-if="!contact.favorite" color='#aaa' name='regular/heart'></icon>
                <icon v-else color='#aaa' name='heart'></icon>
            </div>
            <div @click="destroy()" class='destroy'>
                <icon color='#aaa' name='trash-alt'></icon>
            </div>
        </div>

        <router-link :to="{ name: 'contact-single', params: { contact: contact.id } }">
            <avatar :username='contact.first_name + contact.last_name' :src='contact.avatar' :rounded="true"  :size='64' class='avatar'></avatar>
        </router-link>

        <router-link :to="{ name: 'contact-single', params: { contact: contact.id } }">
            <h2 class="text">{{ contact.first_name }} {{contact.last_name}}</h2>
        </router-link>

    </div>
</template>

<script>

    import Avatar from 'vue-avatar'

    export default {
        
        name: 'contact-component',
        components: {
            Avatar
        },

        props: {
            'contact': {
                type: Object,
                default: null,
            }
        },

        methods: {
            favoriteToggle: function () {
                this.$emit('favoriteToggle', this.contact)
            },
            destroy: function () {
                this.$emit('destroy', this.contact)
            }
        }

    }
</script>

<style lang="scss" scoped>

    $margin: 64px;
    $width: 420px;
    $height: $width/1.7;

    .contact {
        display: inline-block;
        margin: $margin;
        overflow: hidden;
        text-align: center;
        background: #eee;
        border: 3px solid #ddd;
        border-radius: 8px;
        padding: $margin;
        width: $width;
        height: $height;
        position: relative;

        .controls {
            position: absolute;
            top: 0;
            width: 100%;
            left: 0;
            text-align: right;
            padding-right: 8px;
            padding-top: 8px;            
            div {
                display: inline-block;
                font-size: 16px;
                margin: 8px;
            }
            .favorite, .destroy {
                cursor: pointer;
            }
        }

        .avatar {
            
        }
        .text {
            margin-top: $margin/2;
        }
    }
</style>
