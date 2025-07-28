/**
 * Gestion des votes sécurisés pour les reviews
 * Système de vote unique par utilisateur avec possibilité de changer/annuler
 */

class VoteSecureManager {
    constructor() {
        this.initialized = false;
        this.init();
    }

    init() {
        if (this.initialized) {
            return; // Éviter la double initialisation
        }

        document.addEventListener("DOMContentLoaded", () => {
            this.bindEvents();
            this.initialized = true;
        });
    }

    /**
     * Attache les événements aux boutons de vote
     */
    bindEvents() {
        // Utiliser la délégation d'événements pour gérer les boutons ajoutés dynamiquement
        document.addEventListener("click", (e) => {
            if (e.target.closest(".vote-btn-secure")) {
                e.preventDefault();
                this.handleVoteClick(e.target.closest(".vote-btn-secure"));
            }
        });
    }

    /**
     * Gère le clic sur un bouton de vote
     * @param {HTMLElement} button - Le bouton cliqué
     */
    async handleVoteClick(button) {
        const reviewId = button.getAttribute("data-review-id");
        const voteType = button.getAttribute("data-vote-type");
        const voteContainer = button.closest(".vote-container-secure");

        if (!reviewId || !voteType || !voteContainer) {
            console.error("Données manquantes pour le vote");
            return;
        }

        // Désactiver temporairement le bouton
        button.disabled = true;

        // Ajouter une animation de chargement (optionnel)
        this.addLoadingAnimation(button);

        try {
            const response = await fetch(
                `/vote-secure/review/${reviewId}/${voteType}`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                }
            );

            const data = await response.json();

            if (data.success) {
                // Mettre à jour l'interface
                this.updateVoteInterface(voteContainer, data);

                // Afficher un message de succès (optionnel)
                this.showSuccessMessage(data.action, data.message);

                // Animation de feedback
                this.animateButton(button, "success");
            } else {
                console.error("Erreur lors du vote:", data.error);
                this.showError(data.error);
                this.animateButton(button, "error");
            }
        } catch (error) {
            console.error("Erreur réseau:", error);
            this.showError("Erreur de connexion. Veuillez réessayer.");
            this.animateButton(button, "error");
        } finally {
            // Réactiver le bouton et enlever l'animation de chargement
            this.removeLoadingAnimation(button);
            button.disabled = false;
        }
    }

    /**
     * Met à jour l'interface après un vote
     * @param {HTMLElement} container - Le conteneur des boutons de vote
     * @param {Object} data - Les données retournées par le serveur
     */
    updateVoteInterface(container, data) {
        // Mettre à jour les compteurs
        this.updateCounters(container, data);

        // Mettre à jour l'apparence des boutons
        this.updateButtonStates(container, data);

        // Mettre à jour le score
        this.updateScore(container, data);

        // Mettre à jour le pourcentage
        this.updatePercentage(container, data);
    }

    /**
     * Met à jour les compteurs de votes
     */
    updateCounters(container, data) {
        const upCountElement = container.querySelector(".up-count");
        const downCountElement = container.querySelector(".down-count");

        if (upCountElement) {
            upCountElement.textContent = data.upVotes;
        }
        if (downCountElement) {
            downCountElement.textContent = data.downVotes;
        }
    }

    /**
     * Met à jour l'état visuel des boutons
     */
    updateButtonStates(container, data) {
        const upButton = container.querySelector('[data-vote-type="up"]');
        const downButton = container.querySelector('[data-vote-type="down"]');

        if (!upButton || !downButton) return;

        // Reset des classes
        upButton.className = "btn btn-sm vote-btn-secure btn-outline-success";
        downButton.className = "btn btn-sm vote-btn-secure btn-outline-danger";

        // Appliquer le style selon le vote actuel
        if (data.userVoteType === "up") {
            upButton.className = "btn btn-sm vote-btn-secure btn-success";
            upButton.title = "Annuler le like";
            downButton.title = "Je n'aime pas ce commentaire";
        } else if (data.userVoteType === "down") {
            downButton.className = "btn btn-sm vote-btn-secure btn-danger";
            downButton.title = "Annuler le dislike";
            upButton.title = "J'aime ce commentaire";
        } else {
            upButton.title = "J'aime ce commentaire";
            downButton.title = "Je n'aime pas ce commentaire";
        }
    }

    /**
     * Met à jour le score total
     */
    updateScore(container, data) {
        const scoreElement = container.querySelector(".vote-score");

        if (scoreElement) {
            scoreElement.textContent = data.totalScore;

            // Mettre à jour la classe du badge
            scoreElement.className = "vote-score badge ";
            if (data.totalScore > 0) {
                scoreElement.className += "bg-success";
            } else if (data.totalScore < 0) {
                scoreElement.className += "bg-danger";
            } else {
                scoreElement.className += "bg-secondary";
            }
        }
    }

    /**
     * Met à jour le pourcentage de votes positifs
     */
    updatePercentage(container, data) {
        const percentageElement = container.querySelector(".percentage-text");

        if (percentageElement && data.positivePercentage !== undefined) {
            percentageElement.textContent = `${data.positivePercentage}% de votes positifs`;
        }
    }

    /**
     * Ajoute une animation de chargement au bouton
     */
    addLoadingAnimation(button) {
        const icon = button.querySelector("i");
        if (icon) {
            icon.className = "fas fa-spinner fa-spin";
        }
    }

    /**
     * Enlève l'animation de chargement
     */
    removeLoadingAnimation(button) {
        const icon = button.querySelector("i");
        const voteType = button.getAttribute("data-vote-type");

        if (icon && voteType) {
            if (voteType === "up") {
                icon.className = "fas fa-thumbs-up";
            } else {
                icon.className = "fas fa-thumbs-down";
            }
        }
    }

    /**
     * Anime le bouton selon le résultat
     */
    animateButton(button, type) {
        const animationClass =
            type === "success" ? "vote-success" : "vote-error";

        button.classList.add(animationClass);
        setTimeout(() => {
            button.classList.remove(animationClass);
        }, 600);
    }

    /**
     * Affiche un message de succès
     */
    showSuccessMessage(action, message) {
        // Option 1: Console (pour le développement)
        console.log(`Vote ${action}: ${message}`);

        // Option 2: Toast notification (si vous avez Bootstrap Toast ou similaire)
        // this.showToast(message, 'success');

        // Option 3: Message temporaire dans la page
        // this.showTemporaryMessage(message, 'success');
    }

    /**
     * Affiche un message d'erreur
     */
    showError(message) {
        // Option simple: alert (remplacez par votre système de notification)
        alert(message);

        // Alternatives plus élégantes:
        // this.showToast(message, 'error');
        // this.showTemporaryMessage(message, 'error');
    }

    /**
     * Affiche un toast Bootstrap (si disponible)
     */
    showToast(message, type) {
        // Exemple avec Bootstrap Toast
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${
                type === "success" ? "success" : "danger"
            } border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        // Ajouter le toast au conteneur (à adapter selon votre structure)
        const toastContainer =
            document.querySelector(".toast-container") || document.body;
        toastContainer.insertAdjacentHTML("beforeend", toastHtml);

        // Activer le toast
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }
}

// Initialiser le gestionnaire de votes
const voteManager = new VoteSecureManager();
