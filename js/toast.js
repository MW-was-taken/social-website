let x;
function showToast() {
  clearTimeout(x);
  document.getElementById("toast").style.transform = "translateX(0)";
  x = setTimeout(() => {
    document.getElementById("toast").style.transform = "translateX(400px)";
  }, 10000);
}

function closeToast() {
  document.getElementById("toast").style.transform = "translateX(400px)";
}