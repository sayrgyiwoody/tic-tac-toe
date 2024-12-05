<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center p-4"
  >
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-xl">
      <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">
        Send Message to Telegram
      </h1>
      <div
        v-if="successMessage"
        class="my-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md"
      >
        {{ successMessage }}
      </div>
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="space-y-2">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <div class="relative">
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10"
              :class="{ 'border-red-500': errors.name }"
            />
            <UserIcon
              class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"
            />
          </div>
          <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
        </div>

        <div class="space-y-2">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <div class="relative">
            <input
              id="email"
              v-model="form.email"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10"
              :class="{ 'border-red-500': errors.email }"
            />
            <MailIcon
              class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"
            />
          </div>
          <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
        </div>

        <div class="space-y-2">
          <label for="subject" class="block text-sm font-medium text-gray-700"
            >Subject</label
          >
          <div class="relative">
            <input
              id="subject"
              v-model="form.subject"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10"
              :class="{ 'border-red-500': errors.subject }"
            />
            <TagIcon
              class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"
            />
          </div>
          <p v-if="errors.subject" class="text-red-500 text-xs mt-1">
            {{ errors.subject }}
          </p>
        </div>

        <div class="space-y-2">
          <label for="message" class="block text-sm font-medium text-gray-700"
            >Message</label
          >
          <div class="relative">
            <textarea
              id="message"
              v-model="form.message"
              rows="4"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10"
              :class="{ 'border-red-500': errors.message }"
            ></textarea>
            <MessageSquareIcon class="absolute left-3 top-3 text-gray-400 h-5 w-5" />
          </div>
          <p v-if="errors.message" class="text-red-500 text-xs mt-1">
            {{ errors.message }}
          </p>
        </div>

        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <SendIcon v-if="!isSubmitting" class="h-5 w-5 mr-2" />
          <LoaderIcon v-else class="h-5 w-5 mr-2 animate-spin" />
          {{ isSubmitting ? "Sending..." : "Send Message" }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
  UserIcon,
  MailIcon,
  TagIcon,
  MessageSquareIcon,
  SendIcon,
  LoaderIcon,
} from "lucide-vue-next";

const form = reactive({
  name: "",
  email: "",
  subject: "",
  message: "",
});

const errors = reactive({});
const isSubmitting = ref(false);
const successMessage = ref("");

const validateForm = () => {
  errors.name = form.name.length < 2 ? "Name must be at least 2 characters" : "";
  errors.email = !/.+@.+\..+/.test(form.email) ? "Invalid email address" : "";
  errors.subject = form.subject.length < 3 ? "Subject must be at least 3 characters" : "";
  errors.message =
    form.message.length < 10 ? "Message must be at least 10 characters" : "";

  return Object.values(errors).every((error) => error === "");
};

const submitForm = async () => {
  if (!validateForm()) return;

  isSubmitting.value = true;
  successMessage.value = "";

  try {
    // Simulate API call to send message to Telegram
    await new Promise((resolve) => setTimeout(resolve, 1500));

    router.post("/telegram/send-message", form, {
      onSuccess: () => {
        successMessage.value = "Message sent successfully!";
        form.name = "";
        form.email = "";
        form.subject = "";
        form.message = "";
        setTimeout(() => {
          successMessage.value = "";
        }, 4000);
      },
    });
  } catch (error) {
    console.error("Error sending message:", error);
    successMessage.value = "Failed to send message. Please try again.";
  } finally {
    isSubmitting.value = false;
  }
};
</script>
