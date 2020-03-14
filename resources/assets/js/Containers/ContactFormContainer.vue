<template>
  <div class="container contact-form">
    <div class="row">
      <!-- Contact Avatar -->
      <div class="col-md-4 contact-form-left">
        <div class="form-group">
          <picture-input
            ref="pictureInput"
            @change="onAvatarChange"
            width="256"
            height="256"
            margin="0"
            accept="image/jpeg, image/png, image/jpg"
            size="10"
            :prefill="contact.avatar"
            :zIndex="1"
            buttonClass="btn"
            :customStrings="{
                            upload: '<h1>Bummer!</h1>',
                            drag: 'Avatar'
                        }"
          ></picture-input>
        </div>
      </div>

      <!-- General contact info -->

      <div class="col-md-8 contact-form-right">
        <div class="form-group">
          <label for="favorite">
            <p>Favorite</p>
          </label>
          <toggle-button
            v-model="contact.favorite"
            :width="100"
            :height="32"
            :value="false"
            :sync="true"
            :labels="true"
            class="favorite"
            id="favorite"
          />
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="firstName">
                <p>First name</p>
              </label>
              <input
                v-model="contact.first_name"
                type="text"
                class="form-control"
                id="firstName"
                placeholder="Enter first name"
              />
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="lastName">
                <p>Last name</p>
              </label>
              <input
                v-model="contact.last_name"
                type="text"
                class="form-control"
                id="lastName"
                placeholder="Enter last name"
              />
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="email">
                <p>Email</p>
              </label>
              <input
                v-model="contact.email"
                type="email"
                class="form-control"
                id="email"
                placeholder="Enter email"
              />
            </div>
          </div>
        </div>

        <!-- Phones -->
        <div class="contact-form-phones">
          <p>Phones</p>
          <button @click="addPhoneField" class="btn btn-secondary">Add</button>
          <div v-for="(phone, index) in phones" :key="phone.id">
            <div class="row contact-form-phones-item">
              <div class="col">
                <input v-model="phone.number" type="text" class="form-control" placeholder="Number" />
              </div>
              <div class="col">
                <input v-model="phone.label" type="text" class="form-control" placeholder="Label" />
              </div>
              <div class="col">
                <button @click="removePhoneField(index)" class="btn btn-secondary">Remove phone</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Save -->
      <button class="btn btn-success contact-form-save" @click="saveContact">Save Contact</button>

      <!-- Delete -->
      <button
        v-if="contact.id"
        @click="deleteContact(contact.id)"
        class="btn btn-danger contact-form-delete"
      >Delete Contact</button>
    </div>
  </div>
</template>

<script>
import PictureInput from "vue-picture-input";

export default {
  components: {
    PictureInput
  },
  mounted() {
    this.contact_id = this.$route.params.contact;
    if (this.contact_id) {
      this.getContact();
      this.getPhones();
    }
  },
  data() {
    return {
      contact_id: null,

      contact: {
        type: Object,
        default: {
          first_name: null,
          last_name: null,
          favorite: false,
          email: null,
          avatar: null
        }
      },
      phones: []
    };
  },
  methods: {
    removePhoneField: function(index) {
      this.phones.splice(index, 1);
    },
    addPhoneField: function() {
      this.phones.push({
        label: null,
        number: null
      });
    },

    onAvatarChange: function(avatar) {
      if (avatar) {
        this.contact.avatar = this.$refs.pictureInput.file;
      } else {
        console.log("FileReader API not supported: use the <form>, Luke!");
      }
    },

    prepareFormData: function() {
      let formData = new FormData();

      Object.keys(this.contact).map(key => {
        const val = this.contact[key];
        if (!val) return;

        if (key == "avatar") {
          var isFile = typeof val.name == "string";
          if (!isFile) return;
        }

        formData.append(key, val);
      });

      formData.set("favorite", this.contact.favorite ? 1 : 0);

      return formData;
    },

    redirectToContact: function() {
      this.$router.push({
        name: "contact-single",
        params: {
          contact: this.contact_id
        }
      });
    },

    savePhones: function() {
      let url = `/api/contacts/${this.contact_id}/phones`;

      this.phones = this.phones.filter(el => {
        return el.label != null && el.number != null;
      });

      let data = { phones: this.phones };

      axios.post(url, data).then(response => {
        this.redirectToContact();
      });
    },

    saveContact: function() {
      let url = "/api/contacts";
      let data = this.prepareFormData();

      if (this.contact_id) {
        url = `/api/contacts/${this.contact_id}`;
        data.append("_method", "PATCH");
      }

      axios.post(url, data).then(response => {
        this.contact_id = response.data.data.id;
        this.savePhones();
      });
    },

    deleteContact: function(id) {
      let url = `/api/contacts/${id}`;
      axios.delete(url).then(response => {
        this.$router.push({ name: "contact-listing-all" });
      });
    },

    getContact: function() {
      let url = `/api/contacts/${this.contact_id}`;
      axios.get(url).then(response => {
        this.contact = response.data.data;
        this.contact.favorite = this.contact.favorite ? true : false;
      });
    },

    getPhones: function() {
      let url = `/api/contacts/${this.contact_id}/phones`;
      axios.get(url).then(response => {
        this.phones = response.data.data;
      });
    }
  }
};
</script>


<style lang="scss" scoped>
$margin: 64px;

.contact-form {
  margin-top: $margin;

  .vue-js-switch.favorite {
    display: block;
    font-size: 16px;
  }

  .contact-form-phones {
    .contact-form-phones-item {
      margin-top: $margin/2;
    }
  }

  .contact-form-save,
  .contact-form-delete {
    margin: $margin / 2 0;
    width: 100%;
  }
}
</style>
