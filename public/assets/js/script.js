const receiver = document.getElementById("bit_bull_ido_form").contentWindow;
const url = new URL(window.location.href);
const params = new URLSearchParams(url.search);
const ref = params.get("ref");
receiver.postMessage(JSON.stringify({ ref }), "*");