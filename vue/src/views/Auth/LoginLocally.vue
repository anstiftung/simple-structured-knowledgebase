<script setup>
import { reactive, computed, inject } from 'vue'
import CollectionService from '@/services/CollectionService'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { email } from '@vuelidate/validators'
import { required$, maxLength$ } from '@/plugins/validators.js'
import InputWrapper from '@/components/forms/InputWrapper.vue'
import { makeApiRequest } from '@/plugins/api'

const formData = reactive({
  user: {
    email: '',
    password: '',
  },
})

const rules = {
  user: {
    email: { required$, email, $autoDirty: true },
    password: { required$, maxLength: maxLength$(255), $autoDirty: true },
  },
}

const v$ = useVuelidate(rules, formData)

const login = () => {
  const config = {
    method: 'post',
    url: 'auth/login',
    params: {
      email: formData.user.email,
      password: formData.user.password,
    },
  }

  makeApiRequest(config).then(data => {
    console.log(data)
  })
}
</script>
<template>
  <section class="bg-white">
    <div class="py-12 mx-auto w-96 max-w-full">
      <h2 class="text-xl text-blue-600 mb-6">Anmelden</h2>

      <input-wrapper
        v-model="formData.user.email"
        @update:modelValue="v$.user.email.$touch"
        :errors="v$.user.email.$errors"
        label="Benutzername"
        helpText="Der Benutzername für den Login muss eine E-Mail-Adresse eines gültigen Benutzers sein."
        class="mb-4"
      >
        <template #default="{ inputId, modelValue, updateValue }">
          <input
            type="text"
            autofocus
            placeholder="E-Mail Adresse"
            :id="inputId"
            :value="modelValue"
            @input="updateValue"
          />
        </template>
      </input-wrapper>

      <input-wrapper
        v-model="formData.user.password"
        @update:modelValue="v$.user.password.$touch"
        :maxlength="v$.user.password.maxLength.$params.max"
        :errors="v$.user.password.$errors"
        label="Password"
        helpText="Das Passwort sollte mindestens 8 Zeichen lang sein und sollte Groß- und Kleinbuchstaben sowie Sonderzeichen enthalten."
        class="mb-4"
      >
        <template #default="{ inputId, modelValue, updateValue }">
          <input
            type="password"
            autofocus
            placeholder="Passwort"
            :id="inputId"
            :value="modelValue"
            @input="updateValue"
          />
        </template>
      </input-wrapper>

      <button class="default-button large" @click="login">Einloggen</button>
    </div>
  </section>
</template>
