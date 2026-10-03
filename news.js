const newsList = document.getElementById("newsList");

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

fetch("get-news.php")
  .then(function (response) { return response.json(); })
  .then(function (items) {
    if (items.length === 0) {
      newsList.innerHTML = "<p>No news yet.</p>";
      return;
    }

    let html = "";
    items.forEach(function (item) {
      const date = item.event_date ? item.event_date : item.created_at.split(" ")[0];
      html += '<div class="news-card">' +
        '<span class="news-tag ' + item.category.toLowerCase() + '">' + escapeHtml(item.category) + '</span>' +
        '<h3>' + escapeHtml(item.title) + '</h3>' +
        '<p class="news-date">' + escapeHtml(date) + '</p>' +
        '<p>' + escapeHtml(item.content) + '</p>' +
        '</div>';
    });
    newsList.innerHTML = html;
  })
  .catch(function () {
    newsList.innerHTML = "<p>Could not load news.</p>";
  });
  