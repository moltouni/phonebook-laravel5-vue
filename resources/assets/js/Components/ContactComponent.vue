<template>
  <div class="contact">
    <!-- Controls (favorite, delete) -->

    <div class="controls">
      <div @click="favoriteToggle()" class="favorite">
        <icon v-if="!contact.favorite" color="#aaa" name="regular/heart"></icon>
        <icon v-else color="#aaa" name="heart"></icon>
      </div>
      <div @click="destroy()" class="destroy">
        <icon color="#aaa" name="trash-alt"></icon>
      </div>
    </div>

    <!-- Avatar -->

    <router-link :to="{ name: 'contact-single', params: { contact: contact.id } }">
      <avatar
        :username="contact.first_name + contact.last_name"
        :src="contact.avatar"
        :rounded="true"
        :size="64"
        class="avatar"
      ></avatar>
    </router-link>

    <!-- Name -->

    <router-link :to="{ name: 'contact-single', params: { contact: contact.id } }">
      <h2 class="name">{{ contact.first_name }} {{contact.last_name}}</h2>
    </router-link>
  </div>
</template>

<script>
import Avatar from "vue-avatar";

export default {
  name: "contact-component",

  components: {
    Avatar
  },

  props: {
    contact: {
      type: Object,
      default: null
    }
  },

  methods: {
    favoriteToggle: function() {
      this.$emit("favoriteToggle", this.contact);
    },
    destroy: function() {
      this.$emit("destroy", this.contact);
    }
  }
};
</script>

<style lang="scss" scoped>
$width: 420px;
$height: $width/1.7;

$background: #ddd;

.contact {
  display: inline-block;
  position: relative;
  max-width: 100%;
  width: $width;
  height: $height;
  margin: 8px;

  padding: 64px;
  overflow: hidden;
  text-align: center;
  background: $background;
  border-radius: 8px;

  .controls {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;

    padding-right: 8px;
    padding-top: 8px;
    text-align: right;
    font-size: 16px;

    .favorite,
    .destroy {
      display: inline-block;
      margin: 8px;
      cursor: pointer;
    }
  }

  .name {
    margin-top: 32px;
  }
}
</style>
