!function o(s,a,n){function i(r,e){if(!a[r]){if(!s[r]){var t="function"==typeof require&&require;if(!e&&t)return t(r,!0);if(u)return u(r,!0);throw(t=new Error("Cannot find module '"+r+"'")).code="MODULE_NOT_FOUND",t}t=a[r]={exports:{}},s[r][0].call(t.exports,function(e){return i(s[r][1][e]||e)},t,t.exports,o,s,a,n)}return a[r].exports}for(var u="function"==typeof require&&require,e=0;e<n.length;e++)i(n[e]);return i}({1:[function(e,r,t){"use strict";function o(){document.querySelectorAll(".field-msg").forEach(function(e){e.classList.remove("show")})}document.addEventListener("DOMContentLoaded",function(){var t=document.getElementById("myplugin-testimonial-form");t.addEventListener("submit",function(e){e.preventDefault(),o();var r={name:t.querySelector('[name="name"]').value,eamil:t.querySelector('[name="email"]').value,message:t.querySelector('[name="message"]').value};r.name?/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(r.eamil).toLowerCase())?r.message?(e=t.dataset.url,r=new URLSearchParams(new FormData(t)),t.querySelector(".js-form-submission").classList.add("show"),fetch(e,{method:"POST",body:r}).then(function(e){return e.json()}).catch(function(e){o(),t.querySelector(".js-form-error").classList.add("show"),alert("error submiting form")}).then(function(e){o(),0!==e&&"error"!==e.status?(t.querySelector(".js-form-success").classList.add("show"),t.reset()):t.querySelector(".js-form-error").classList.add("show")})):t.querySelector('[data-error="invalidMessage"]').classList.add("show"):t.querySelector('[data-error="invalidEmail"]').classList.add("show"):t.querySelector('[data-error="invalidName"]').classList.add("show")})})},{}]},{},[1]);
//# sourceMappingURL=form.js.map
