<script setup>
import { storeToRefs } from 'pinia'
import { useUserStore } from '../stores/user';

// If you need UserPermissions, you'll need the next three lines
const store = useUserStore()
const { hasPermission } = storeToRefs(store)
store.initUser()

</script>
<template>
    <section class="bg-white">
        <!-- todo: generalize -->
        <div class="py-12 width-wrapper">
            <p class="mb-2">Dieses Profil ist nur sichtbar wenn die Keycloak-Auth erfolgreich war.</p>

            <div v-if="store.id">
                <section class="mb-4">
                    <p><strong>Benutzername:</strong> {{store.name}}</p>
                    <p><strong>E-Mail Adresse:</strong> {{store.email}}</p>
                </section>
                <section class="mb-4">
                    <button class="default-button">Anhang erstellen</button>
                </section>
                <section class="mb-4">
                    <button class="default-button">Beitrag erstellen</button>
                </section>
                <section class="mb-4" v-if="hasPermission('add collections')">
                    <button class="default-button">Sammlung erstellen</button>
                </section>

                <section class="mb-4">
                    <router-link :to="{name:'logout'}" class="default-button">Abmelden</router-link>
                </section>
            </div>
            <div v-else>
                <p><strong>Fehler:</strong> Du bist nicht authorisiert diese Inhalte zu sehen.</p>
            </div>
        </div>
    </section>
</template>
