<template>
  <Head title="Tic Tac Toe" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Tic Tac Toe</h2>
    </template>

    <div class="py-12 px-3">
      <div
        class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-4 md:p-0 md:px-6 md:py-4 rounded-md"
      >
        <div class="flex flex-col md:flex-row items-start gap-4 md:items-center md:justify-between mb-6">
          <Link
            :href="route('games.store')"
            method="POST"
            as="button"
            class="flex items-center justify-center cursor-pointer gap-2 rounded-md border border-transparent bg-gray-700 hover:bg-gray-800 px-4 py-3 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 active:bg-gray-900"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 inline-block"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              />
            </svg>
            Create New Game
          </Link>

          <div class="relative w-full max-w-xs">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by name..."
              class="w-full px-4 py-2 border-2 rounded-md dark:bg-gray-700 dark:text-gray-50 dark:border-gray-600"
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-4.35-4.35m1.75-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
              />
            </svg>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800"
            v-for="game in games"
            :key="game.id"
          >
            <div class="flex justify-between items-center mb-4">
              <div class="flex gap-2 dark:text-gray-50">
                <p>{{ game.player_one.name }}</p>
                |
                <p>{{ formatDate(game.created_at) }}</p>
              </div>
            </div>
            <Link
              :href="route('games.join', game)"
              method="POST"
              as="button"
              class="w-full group dark:text-gray-50 dark:bg-blue-500 dark:border-gray-300 flex items-center justify-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 dark:hover:bg-blue-600 focus:ring-4 focus:ring-gray-100 hover:text-blue-600 font-medium rounded-lg text-sm px-5 py-2.5"
            >
              <svg
                class="w-4 h-4 me-2 transition-transform duration-300 group-hover:rotate-45"
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
              >
                <path
                  fill="currentColor"
                  d="M6.125 12.15q.65-1.65 1.563-3.175T9.75 6.1l-1.3-.275q-.5-.1-.975.05t-.825.5L3.5 9.525q-.375.375-.288.9t.588.725zm14.7-9.75q-2.65-.125-5.037 1.025T11.525 6.45q-1.2 1.2-2.113 2.6T7.826 12q-.125.325-.125.663t.25.587l3.125 3.125q.25.25.588.25t.662-.125q1.55-.675 2.95-1.588t2.6-2.112q1.875-1.875 3.025-4.262T21.925 3.5q0-.2-.1-.4t-.25-.35t-.35-.25t-.4-.1m-6.35 7.45q-.575-.575-.575-1.412t.575-1.413t1.413-.575t1.412.575t.575 1.413T17.3 9.85t-1.412.575t-1.413-.575m-2.3 8.35l1 2.325q.2.5.725.6t.9-.275l3.15-3.15q.35-.35.5-.837t.05-.988l-.25-1.3q-1.375 1.15-2.887 2.063T12.175 18.2M4.05 16.05q.875-.875 2.125-.888t2.125.863t.875 2.125t-.875 2.125q-1.2 1.2-2.838 1.425t-3.287.45q.225-1.65.463-3.287T4.05 16.05"
                />
              </svg>
              Join Game
            </Link>
          </div>
        </div>
        <!-- Pagination Links -->
        <div class="mt-4 flex justify-center gap-4">
          <button
            v-if="props.games.prev_page_url"
            @click="goToPage(props.games.prev_page_url)"
            class="flex items-center bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-50 px-4 py-2 rounded-md transition duration-150 ease-in-out hover:bg-gray-300 dark:hover:bg-gray-600"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              />
            </svg>
            Previous
          </button>
          <button
            v-if="props.games.next_page_url"
            @click="goToPage(props.games.next_page_url)"
            class="flex items-center bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-50 px-4 py-2 rounded-md transition duration-150 ease-in-out hover:bg-gray-300 dark:hover:bg-gray-600"
          >
            Next
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5 ml-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { defineProps, ref, watch } from "vue";
import { formatDistanceToNow } from "date-fns";

const props = defineProps(["games"]);

const games = ref(props.games.data);

Echo.private("lobby").listen("GameJoined", (event) => {
  games.value = games.value.filter((game) => game.id !== event.game.id);

  if (games.value.length < 5) {
    router.reload({ only: ["games"], onSuccess: () => (games.value = props.games.data) });
  }
});
Echo.private("games").listen("GameCreated", (event) => {
  const existingGameIndex = games.value.findIndex((game) => {
    return game.player_one.id === event.game.player_one.id;
  });
  if (existingGameIndex !== -1) {
    games.value.splice(existingGameIndex, 1);
  }
  games.value.unshift(event.game);

  if (games.value.length > 5) {
    router.reload({ only: ["games"], onSuccess: () => (games.value = props.games.data) });
  }
});

const formatDate = (created_at) => {
  return formatDistanceToNow(new Date(created_at), { addSuffix: true });
};

const goToPage = (url) => {
  // Use Inertia to visit the pagination URL
  router.visit(url);
};

// Declare a reactive search query
const searchQuery = ref("");

// Watch for changes in searchQuery
watch(searchQuery, (val) => {
  if (val === "") {
    // Reset games data if search query is empty
    games.value = props.games.data;
  } else {
    // Perform the search if query is not empty
    router.get(
      route("games.index"), // Define this route in your web.php
      { search: val }, // Pass the search query as a parameter
      {
        preserveState: true, // Prevent full page reload, keep state
        onSuccess: (page) => {
          // Update the games list with the filtered data
          games.value = page.props.games.data;
        },
      }
    );
  }
});
</script>


