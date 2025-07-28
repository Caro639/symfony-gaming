import "bootstrap";

// Import du système de vote sécurisé
import "./vote-secure.js";

// Gestion des votes sur les reviews
document.addEventListener("DOMContentLoaded", function () {
    // Sélectionner tous les boutons de vote
    const voteButtons = document.querySelectorAll(".vote-btn");

    voteButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const reviewId = this.getAttribute("data-review-id");
            const voteType = this.getAttribute("data-vote-type");
            const voteContainer = this.closest(".vote-container");

            // Désactiver temporairement le bouton pour éviter les clics multiples
            this.disabled = true;

            // Envoyer la requête AJAX
            fetch(`/vote/review/${reviewId}/${voteType}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Mettre à jour les compteurs
                        const upCountElement =
                            voteContainer.querySelector(".up-count");
                        const downCountElement =
                            voteContainer.querySelector(".down-count");
                        const scoreElement =
                            voteContainer.querySelector(".vote-score");

                        if (upCountElement)
                            upCountElement.textContent = data.upVotes;
                        if (downCountElement)
                            downCountElement.textContent = data.downVotes;

                        // Mettre à jour le score total
                        if (scoreElement) {
                            scoreElement.textContent = data.totalScore;

                            // Mettre à jour la classe CSS du badge selon le score
                            scoreElement.classList.remove(
                                "bg-success",
                                "bg-danger",
                                "bg-secondary"
                            );
                            if (data.totalScore > 0) {
                                scoreElement.classList.add("bg-success");
                            } else if (data.totalScore < 0) {
                                scoreElement.classList.add("bg-danger");
                            } else {
                                scoreElement.classList.add("bg-secondary");
                            }
                        }

                        // Mettre à jour le pourcentage si présent
                        const percentageElement =
                            voteContainer.querySelector(".percentage-text");
                        if (
                            percentageElement &&
                            data.positivePercentage !== undefined
                        ) {
                            percentageElement.textContent = `${data.positivePercentage}% de votes positifs`;
                        }

                        // Animation de feedback visuel
                        this.classList.add("btn-pulse");
                        setTimeout(() => {
                            this.classList.remove("btn-pulse");
                        }, 300);
                    } else {
                        console.error("Erreur lors du vote:", data.error);
                        alert("Erreur lors du vote. Veuillez réessayer.");
                    }
                })
                .catch((error) => {
                    console.error("Erreur:", error);
                    alert("Erreur de connexion. Veuillez réessayer.");
                })
                .finally(() => {
                    // Réactiver le bouton
                    this.disabled = false;
                });
        });
    });
});
