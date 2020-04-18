// Likes
document.addEventListener("DOMContentLoaded", () => {
    let likeButtons = document.querySelectorAll(".like__button");

    if (likeButtons) {
        document.addEventListener("click", (event) => {
            if (!event.target.classList.contains("like__button"))
                return ;
            event.preventDefault();

            let photo = event.target;
            let photoId = photo.dataset.id;

            fetch("/photo/like", {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/json"
                },
                body: JSON.stringify({"id": photoId})
            })
            .then((result) => result.text())
            .then((result) => {
                if (result == "Success")
                    toggleLike(photo);
            });
            
        });
    }
    function toggleLike(photo) {
        let number = photo.parentNode.querySelector(".like__number");
        let diff = photo.classList.contains("like__button_active") ? -1 : 1;
        
        number.innerText = parseInt(number.innerText) + diff;
        photo.classList.toggle("like__button_active");
    }
});