import 'es6-promise/auto'
import 'bootstrap'
import axios from 'axios'
import './vue'
import shuffle from 'shufflejs'
window.Shuffle = shuffle;

// Set axios default headers passed with each request
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF TOKEN NOT FOUND');
}
