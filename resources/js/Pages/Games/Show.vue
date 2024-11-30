<template>
  <AuthenticatedLayout>
    <div class="flex items-center justify-between py-4 px-6">
      <Link :href="route('games.index')" class="flex items-center text-white">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          class="w-6 h-6 mr-2"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back
      </Link>
    </div>
    <div class="p-3">
      <div
        class="flex items-center justify-between py-4 px-6 bg-blue-800 dark:bg-blue-900 rounded-lg shadow-lg max-w-md mx-auto mt-12"
      >
        <!-- Player One -->
        <div class="flex flex-col items-center">
          <!-- Profile Picture with Border -->
          <div class="relative">
            <img
              :src="getAvatarUrl(game.player_one.name)"
              alt="player name"
              :class="{ 'border-4 border-green-500': xTurn }"
              class="w-20 h-20 rounded-full"
            />
            <!-- Active Status Indicator -->
            <div
              :class="{
                '!bg-green-500': players.find(({ id }) => id === game.player_one_id),
              }"
              class="transition-colors absolute bottom-0 right-0 w-6 h-6 bg-red-500 text-white text-sm flex items-center justify-center rounded-full border-2 border-blue-900"
            ></div>
          </div>
          <!-- Player Name -->
          <p class="capitalize mt-2 text-white font-semibold">
            {{ game.player_one.name }}
          </p>

          <p class="text-5xl font-bold text-emerald-600 dark:text-emerald-500">X</p>
        </div>

        <!-- <div v-if="gameState.hasEnded" class="flex flex-col items-center text-white">
          <svg class="" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M15 3.25a.75.75 0 0 1 .75.75v1A1.75 1.75 0 0 1 14 6.75h-1a.25.25 0 0 0-.25.25v1h.422c2.54 0 3.809 0 4.785.614q.371.233.683.542c.82.81 1.11 2.047 1.687 4.52l1.023 4.373A2.858 2.858 0 0 1 16 19.957l-.12-.246a3.2 3.2 0 0 0-2.877-1.794h-2.005a3.2 3.2 0 0 0-2.876 1.794l-.12.246a2.858 2.858 0 0 1-5.35-1.908l1.022-4.374c.578-2.472.867-3.708 1.686-4.519a4 4 0 0 1 .684-.542C7.02 8 8.29 8 10.83 8h.422V7c0-.966.783-1.75 1.75-1.75h1a.25.25 0 0 0 .25-.25V4a.75.75 0 0 1 .75-.75M16 12a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-7.5-.75a.75.75 0 0 1 .75.75v.75H10a.75.75 0 0 1 0 1.5h-.75V15a.75.75 0 0 1-1.5 0v-.75H7a.75.75 0 0 1 0-1.5h.75V12a.75.75 0 0 1 .75-.75M17 15.5a1 1 0 1 0 0-2a1 1 0 0 0 0 2" clip-rule="evenodd"/></svg>
          <p class="text-sm md:text-base text-center w-32 md:w-40 mt-2 ">
           Game Over !
          </p>
        </div> -->
        <!-- Turn Arrow -->
        <div
          v-if="yourTurn && game.player_two_id"
          class="flex flex-col items-center text-white"
        >
          <p class="text-sm md:text-base text-center w-32 md:w-40">It's Your Turn !</p>

          <div class="animate-bounce mt-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              class="w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19.5 12.75L12 19.5m0 0L4.5 12.75M12 19.5V4.5"
              />
            </svg>
          </div>
        </div>

        <div v-else-if="!yourTurn" class="flex flex-col items-center text-white">
          <svg
            class="animate-bounce"
            xmlns="http://www.w3.org/2000/svg"
            width="32"
            height="32"
            viewBox="0 0 24 24"
          >
            <path
              fill="currentColor"
              fill-rule="evenodd"
              d="M15 3.25a.75.75 0 0 1 .75.75v1A1.75 1.75 0 0 1 14 6.75h-1a.25.25 0 0 0-.25.25v1h.422c2.54 0 3.809 0 4.785.614q.371.233.683.542c.82.81 1.11 2.047 1.687 4.52l1.023 4.373A2.858 2.858 0 0 1 16 19.957l-.12-.246a3.2 3.2 0 0 0-2.877-1.794h-2.005a3.2 3.2 0 0 0-2.876 1.794l-.12.246a2.858 2.858 0 0 1-5.35-1.908l1.022-4.374c.578-2.472.867-3.708 1.686-4.519a4 4 0 0 1 .684-.542C7.02 8 8.29 8 10.83 8h.422V7c0-.966.783-1.75 1.75-1.75h1a.25.25 0 0 0 .25-.25V4a.75.75 0 0 1 .75-.75M16 12a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-7.5-.75a.75.75 0 0 1 .75.75v.75H10a.75.75 0 0 1 0 1.5h-.75V15a.75.75 0 0 1-1.5 0v-.75H7a.75.75 0 0 1 0-1.5h.75V12a.75.75 0 0 1 .75-.75M17 15.5a1 1 0 1 0 0-2a1 1 0 0 0 0 2"
              clip-rule="evenodd"
            />
          </svg>
          <p class="text-sm md:text-base text-center w-32 md:w-40 mt-2 animate-pulse">
            Wait for other player ...
          </p>
        </div>

        <div v-else class="flex flex-col items-center text-white">
          <Loader></Loader>

          <p class="text-sm md:text-base text-center w-32 md:w-40 mt-2 animate-pulse">
            Wait for other player to join ...
          </p>
        </div>

        <!-- Player Two -->
        <div v-if="game.player_two_id" class="flex flex-col items-center">
          <!-- Profile Picture with Border -->
          <div class="relative">
            <img
              :src="getAvatarUrl(game.player_two.name)"
              alt="player name"
              :class="{ 'border-4 border-green-500': !xTurn }"
              class="w-20 h-20 rounded-full"
            />
            <!-- Active Status Indicator -->
            <div
              :class="{
                '!bg-green-500': players.find(({ id }) => id === game.player_two_id),
              }"
              class="transition-colors absolute bottom-0 right-0 w-6 h-6 bg-red-500 text-white text-sm flex items-center justify-center rounded-full border-2 border-blue-900"
            ></div>
          </div>
          <!-- Player Name -->
          <p class="capitalize mt-2 text-white font-semibold">
            {{ game.player_two.name }}
          </p>
          <p class="text-5xl font-bold text-rose-600 dark:text-rose-500">O</p>
        </div>

        <!-- Waiting Player -->
        <div v-else class="flex flex-col items-center">
          <!-- Profile Picture with Border -->
          <div class="relative">
            <img
              src="/img/unknown_person.jpg"
              alt="player name"
              class="w-20 h-20 rounded-full border-4 border-transparent"
            />
            <!-- Active Status Indicator -->
            <div
              class="transition-colors absolute bottom-0 right-0 w-6 h-6 bg-gray-400 text-white text-sm flex items-center justify-center rounded-full border-2 border-blue-900"
            ></div>
          </div>
          <!-- Player Name -->
          <p class="capitalize mt-2 text-white font-semibold">Other Player</p>
          <p class="text-5xl font-bold text-rose-600 dark:text-rose-500">O</p>
        </div>
      </div>

      <menu class="max-w-md mx-auto mt-4">
        <div class="grid grid-cols-3 p-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
          <li
            v-for="(square, index) in boardState"
            :key="index"
            class="size-32 grid place-items-center p-2 bg-white dark:bg-gray-800"
          >
            <button
              @click="fillSquare(index)"
              :disabled="!yourTurn || !game.player_two_id"
              v-if="square === 0"
              class="place-self-stretch rounded-lg border-2 border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-500 hover:shadow hover:scale-[103%] hover:bg-gray-50 dark:hover:bg-gray-600 disabled:hover:scale-100 disabled:hover:shadow-none disabled:hover:bg-white dark:disabled:hover:bg-gray-700 transition-all disabled:hover:none disabled:cursor-default"
            ></button>
            <span
              v-else
              v-text="square === -1 ? 'X' : 'O'"
              :class="
                square === -1
                  ? 'text-emerald-600 dark:text-emerald-500'
                  : 'text-rose-600 dark:text-rose-500'
              "
              class="text-5xl font-bold cursor-default place-self-stretch bg-white dark:bg-gray-700 dark:border-gray-500 flex justify-center items-center rounded-lg border-2 border-gray-300"
            ></span>
          </li>
        </div>
      </menu>
      <click-to-chat v-if="game.player_two_id" :game="game" :auth="auth"></click-to-chat>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, computed, defineProps, onUnmounted, onMounted, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { useGameState, gameStates } from "@/Composables/useGameState";
import { Inertia } from '@inertiajs/inertia';

const props = defineProps(["game", "auth"]);

const boardState = ref(props.game.state || [0, 0, 0, 0, 0, 0, 0, 0, 0]);

const gameState = useGameState();

const players = ref([]);

const xTurn = computed(
  () => boardState.value.reduce((carry, value) => carry + value, 0) === 0
);

const getAvatarUrl = (name) => {
  //   return `https://ui-avatars.com/api/?background=2563eb&color=ffffff&name=${encodeURIComponent(name)}`;
  return "/img/person.png";
};

const yourTurn = computed(() => {
  if (props.game.player_one_id === props.auth?.user.id) {
    return xTurn.value;
  }
  return !xTurn.value;
});

const lines = [
  // rows
  [0, 1, 2],
  [3, 4, 5],
  [6, 7, 8],
  // columns
  [0, 3, 6],
  [1, 4, 7],
  [2, 5, 8],
  // diagonals
  [0, 4, 8],
  [2, 4, 6],
];

const checkForVictory = () => {
  const winningLine = lines
    .map((line) => line.reduce((carry, index) => carry + boardState.value[index], 0))
    .find((sum) => Math.abs(sum) === 3);

  if (winningLine === -3) {
    gameState.change(gameStates.XWins);
    return;
  }

  if (winningLine === 3) {
    gameState.change(gameStates.OWins);
    return;
  }

  if (!boardState.value.includes(0)) {
    gameState.change(gameStates.Stalemate);
    return;
  }

  gameState.change(gameStates.InProgress);
};

const updateOpponent = () => {
  router.put(route("games.update", props.game.id), {
    state: boardState.value,
  });

  channel.whisper("PlayerMadeMove", {
    state: boardState.value,
  });
};

watch(gameState.hasEnded, (hasEnded) => {
  if (hasEnded) {
    Swal.fire({
      title: "Game Over",
      text:
        gameState.current() === gameStates.XWins
          ? "Player X Wins!"
          : gameState.current() === gameStates.OWins
          ? "Player O Wins!"
          : "It's a Stalemate!",
      icon: "info",
      confirmButtonText: "Restart Game",
      showCancelButton: true,
      allowEscapeKey: false,
      allowOutsideClick: false,
    }).then((result) => {
        if(result.isConfirmed) {
          resetGame();
        }
    });
  }
});

const channel = Echo.join(`game.${props.game.id}`)
    .here((users) => {
        players.value = users;
    })
    .joining((user) => {
        router.reload({
            onSuccess: () => {
                players.value.push(user);
            },
        });
    })
    .leaving((user) => (players.value = players.value.filter((u) => u.id !== user.id)))
    .listenForWhisper("PlayerMadeMove", ({ state }) => {
        boardState.value = state;
        checkForVictory();

        if (gameState.current() === gameStates.InProgress) {
            Swal.close();
        }
    });

const fillSquare = (index) => {
  if (!yourTurn.value) {
    return;
  }

  boardState.value[index] = xTurn.value ? -1 : 1;

  updateOpponent();
  checkForVictory();
};

const resetGame = () => {
  boardState.value = [0, 0, 0, 0, 0, 0, 0, 0, 0];
  gameState.change(gameStates.InProgress);

  updateOpponent();
};

onMounted(() => {
  checkForVictory();
});

onUnmounted(() => {
  Echo.leave(`game.${props.game.id}`);
});
</script>

<script>
import Loader from "@/Components/Loader.vue";
import ClickToChat from "@/Components/Game/ClickToChat.vue";

export default {
  components: {
        Loader,
        ClickToChat,
  },
};
</script>
