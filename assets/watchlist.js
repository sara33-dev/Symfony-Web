
document.querySelector("#watchlist").addEventListener('click', addToWatchlist);
function addToWatchlist(event) {
    event.preventDefault();
    let watchlistLink = event.currentTarget;
    let link = watchlistLink.href;
    fetch(link)
        .then(res => res.json())
        .then(function(res) {
            let watchlistIcon = watchlistLink.firstElementChild;
            if (res.isInWatchlist) {
                watchlistIcon.classList.remove('far');
                watchlistIcon.classList.add('fas');
            } else {
                watchlistIcon.classList.remove('fas');
                watchlistIcon.classList.add('far');
            }
        });
}
