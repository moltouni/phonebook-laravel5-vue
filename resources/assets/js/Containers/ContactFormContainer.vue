<template>
  <div class="container contact-form">
    <div class="row">
      <div class="col-md-12">
        <!-- Avatar -->

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

        <!-- Favorite -->

        <div class="form-group">
          <label for="favorite">
            <h5>Favorite</h5>
          </label>
          <toggle-button
            v-model="contact.favorite"
            :width="100"
            :height="32"
            :value="false"
            :sync="true"
            :labels="false"
            class="favorite"
            id="favorite"
          />
        </div>

        <!-- First Name -->

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="firstName">
                <h5>First name *</h5>
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

        <!-- Last Name -->

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="lastName">
                <h5>Last name *</h5>
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

        <!-- Email -->

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="email">
                <h5>Email</h5>
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
          <h5>Phones</h5>

          <button @click="addPhoneField" class="btn btn-outline-secondary w-100">Add</button>

          <div v-for="(phone, index) in phones" :key="phone.id">
            <div class="row contact-form-phones-item">
              <!-- Label -->

              <div class="col">
                <div class="form-group">
                  <label :for="`phoneLabel-${index}`">Label *</label>
                  <input
                    v-model="phone.label"
                    type="text"
                    class="form-control"
                    :id="`phoneLabel-${index}`"
                    placeholder="Label"
                  />
                </div>
              </div>

              <!-- Number -->

              <div class="col">
                <div class="form-group">
                  <label :for="`phoneNumber-${index}`">Number *</label>
                  <input
                    v-model="phone.number"
                    type="text"
                    class="form-control"
                    :id="`phoneNumber-${index}`"
                    placeholder="Number"
                  />
                </div>
              </div>

              <!-- Delete -->

              <div class="col">
                <div class="form-group">
                  <label :for="`phoneRemove-${index}`">Remove</label>
                  <button
                    @click="removePhoneField(index)"
                    :id="`phoneRemove-${index}`"
                    class="btn btn-outline-secondary d-block"
                  >
                    <icon name="times"></icon>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Controls -->

        <div class="text-center">
          <!-- Save -->

          <button
            :disabled="!isFormDataValid"
            class="btn btn-outline-success contact-form-save w-100"
            @click="saveContact(contact, phones)"
          >Save Contact</button>

          <!-- Delete -->

          <button
            v-if="contact.id"
            @click="deleteContact(contact.id)"
            class="btn btn-outline-danger contact-form-delete w-100"
          >Delete Contact</button>
        </div>
      </div>
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
    let contactId = this.$route.params.contact;

    if (contactId) {
      this.getContact(contactId);
      this.getContactPhones(contactId);
    }
  },
  data() {
    return {
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
  computed: {
    isFormDataValid() {
      return this.contact.first_name && this.contact.last_name;
    }
  },
  methods: {
    /**
     * Add Phone Field
     */
    addPhoneField: function() {
      this.phones.push({
        label: null,
        number: null
      });
    },

    /**
     * Remove Phone Field
     *
     * @param {Number} index Index of the field
     */
    removePhoneField: function(index) {
      this.phones.splice(index, 1);
    },

    /**
     * On Avatar Change
     *
     * @param {*} avatar Avatar
     */
    onAvatarChange: function(avatar) {
      if (avatar) {
        this.contact.avatar = this.$refs.pictureInput.file;
      } else {
        console.log("FileReader API not supported: use the <form>, Luke!");
      }
    },

    /**
     * Save Contact
     *
     * @param {Object} contact New or existing contact data
     * @param {Array} phones Phones of the contact
     */
    saveContact: function(contact, phones) {
      let url = null;
      let data = this.getContactFormData(contact);

      if (!contact.id) {
        url = "/api/contacts";
      } else {
        url = `/api/contacts/${contact.id}`;
        data.append("_method", "PATCH");
      }

      axios.post(url, data).then(response => {
        this.contact = response.data.data;
        this.saveContactPhones(this.contact.id, phones);
      });
    },

    /**
     * Get Contact FormData
     *
     * @param {Object} contact Contact
     */
    getContactFormData: function(contact) {
      let formData = new FormData();

      Object.keys(contact).map(key => {
        const val = contact[key];
        if (!val) return;

        if (key == "avatar") {
          var isFile = typeof val.name == "string";
          if (!isFile) return;
        }

        formData.append(key, val);
      });

      formData.set("favorite", contact.favorite ? 1 : 0);

      return formData;
    },

    /**
     * Save Contact Phones
     *
     * @param {Number} contactId Contact Id
     * @param {Array} phones Phones
     */
    saveContactPhones: function(contactId, phones) {
      let url = `/api/contacts/${contactId}/phones`;

      phones = phones.filter(el => {
        return el.label != null && el.number != null;
      });

      let data = { phones: phones };

      axios.post(url, data).then(response => {
        this.redirectToContact(contactId);
      });
    },

    /**
     * Redirect to Contact page
     *
     * @param {Number} contactId Contact ID
     */
    redirectToContact: function(contactId) {
      this.$router.push({
        name: "contact-single",
        params: {
          contact: contactId
        }
      });
    },

    /**
     * Get Contact
     *
     * @param {Number} id Contact Id
     */
    getContact: function(id) {
      let url = `/api/contacts/${id}`;

      axios.get(url).then(response => {
        this.contact = response.data.data;
        this.contact.favorite = this.contact.favorite ? true : false;
      });
    },

    /**
     * Get Contact Phones
     *
     * @param {Number} id Contact Id
     */
    getContactPhones: function(id) {
      let url = `/api/contacts/${id}/phones`;

      axios.get(url).then(response => {
        this.phones = response.data.data;
      });
    },

    /**
     * Delete Contact
     *
     * @param {Number} id Contact Id
     */
    deleteContact: function(id) {
      let url = `/api/contacts/${id}`;

      axios.delete(url).then(response => {
        this.$router.push({ name: "contact-listing-all" });
      });
    }
  }
};
</script>


<style lang="scss" scoped>
$margin: 64px;
$background: #eee;
$border: 3px solid #ddd;
.contact-form {
  margin-top: $margin;

  & > .row {
    margin: $margin 0;
    padding: $margin 0;
    background: $background;
    border: $border;
    border-radius: 8px;
  }

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
    margin: $margin / 2 0 0;
  }
}
</style>
