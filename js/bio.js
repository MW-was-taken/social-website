function readMore() {
  console.log("readMore");
  var readMore = document.getElementById("read-more");
  var readLess = document.getElementById("read-less");
  var btn = document.getElementById("btn");
  var dots = document.getElementById("dots");

  if (readMore.style.display === "none") {
    readMore.style.display = "inline";
    readMore.style.opacity = "1";
    btn.innerHTML = "Read Less";
    dots.style.display = "none";
  } else {
    readMore.style.display = "none";
    readMore.style.opacity = "0";
    readLess.style.display = "inline";
    readLess.style.opacity = "1";
    btn.innerHTML = "Read More";
    dots.style.display = "inline";
  }
}