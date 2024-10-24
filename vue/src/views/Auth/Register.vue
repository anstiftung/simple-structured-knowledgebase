<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { email } from '@vuelidate/validators'
import { required$, maxLength$ } from '@/plugins/validators.js'
import InputWrapper from '@/components/forms/InputWrapper.vue'
import { makeApiRequest } from '@/plugins/api'
import ToastPlugin from '@/plugins/toast.js'

const formData = reactive({
  user: {
    name: '',
    email: '',
    password: '',
    password_comfirmation: '',
  },
})

const router = useRouter()

const rules = {
  user: {
    name: { required$, maxLength: maxLength$(255), $autoDirty: true },
    email: { required$, email, $autoDirty: true },
    password: { required$, maxLength: maxLength$(255), $autoDirty: true },
    password_confirmation: {
      required$,
      maxLength: maxLength$(255),
      $autoDirty: true,
    },
  },
}

const v$ = useVuelidate(rules, formData)

const login = () => {
  const config = {
    method: 'post',
    url: 'auth/register',
    params: formData.user,
  }

  makeApiRequest(config).then(data => {
    ToastPlugin.success('Registrierung erfolgreich! Bitte einloggen.')
    router.push({ name: 'login' })
  })
}
</script>
<template>
  <section class="bg-white">
    <div class="py-12 mx-auto w-96 max-w-full">
      <h2 class="text-xl text-blue-600 mb-6">Registrierung</h2>

      <input-wrapper
        v-model="formData.user.name"
        @update:modelValue="v$.user.name.$touch"
        :errors="v$.user.name.$errors"
        label="Benutzername"
        helpText="Der Benutzername muss darf maximal 255 Zeichen lang sein."
        class="mb-4"
      >
        <template #default="{ inputId, modelValue, updateValue }">
          <input
            type="text"
            autofocus
            placeholder="Nickname"
            :id="inputId"
            :value="modelValue"
            @input="updateValue"
          />
        </template>
      </input-wrapper>

      <input-wrapper
        v-model="formData.user.email"
        @update:modelValue="v$.user.email.$touch"
        :errors="v$.user.email.$errors"
        label="e-Mail"
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

      <input-wrapper
        v-model="formData.user.password_confirmation"
        @update:modelValue="v$.user.password_confirmation.$touch"
        :maxlength="v$.user.password_confirmation.maxLength.$params.max"
        :errors="v$.user.password_confirmation.$errors"
        label="Passwort Bestätigung"
        helpText="Das Passwort sollte mindestens 8 Zeichen lang sein und sollte Groß- und Kleinbuchstaben sowie Sonderzeichen enthalten."
        class="mb-4"
      >
        <template #default="{ inputId, modelValue, updateValue }">
          <input
            type="password"
            autofocus
            placeholder="Passwort erneut eingeben"
            :id="inputId"
            :value="modelValue"
            @input="updateValue"
          />
        </template>
      </input-wrapper>

      <button class="default-button large" @click="login">Registrieren</button>
    </div>
  </section>
</template>
