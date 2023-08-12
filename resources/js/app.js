import './bootstrap';

import './helpers';

import { createApp } from 'vue';
import VideoChat from './components/VideoChat.vue';

var EmojiConvertor = require('emoji-js');

window.emoji = new EmojiConvertor();



const app = createApp();

app.component('video-chat', VideoChat);

app.mount("#main-content");


