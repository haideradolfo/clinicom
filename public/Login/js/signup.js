document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[name="user_form"]');
    const fieldsConfig = {
        nom: {
            regex: /^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/,
            error: "Doit commencer par une majuscule (lettres, apostrophes et traits d'union seulement)"
        },
        prenom: {
            regex: /^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/,
            error: "Doit commencer par une majuscule"
        },
        specialite: {
            regex: /^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/,
            error: "Doit commencer par une majuscule"
        },
        email: {
            regex: /^[a-z0-9]+\.[a-z0-9]+@[a-z]+\.[a-z]{2,}$/i,
            error: "Format invalide (ex: jean.dupont@clinicom.fr)"
        },
        adresse: {
            regex: /^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ0-9][a-zà-éèêëîïôùûüçœ0-9\-, ']*$/,
            error: "Doit commencer par une majuscule ou un chiffre"
        },
        ville: {
            regex: /^[A-ZÀ-ÉÈÊËÎÏÔÙÛÜÇŒœ][a-zà-éèêëîïôùûüçœ\- ']*$/,
            error: "Doit commencer par une majuscule"
        },
        mdp: {
            regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            error: "8 caractères min, 1 majuscule, 1 chiffre, 1 caractère spécial"
        }
    };

    // Initialisation des écouteurs d'événements
    Object.keys(fieldsConfig).forEach(field => {
        const input = document.getElementById(user_$,{field});
        if (input) {
            // Validation en temps réel
            input.addEventListener('input', () => validateField(input, fieldsConfig[field]));
            // Validation supplémentaire après la saisie
            input.addEventListener('blur', () => validateField(input, fieldsConfig[field]));
        }
    });

    // Gestion de la date de naissance
    const dateNaissanceInput = document.getElementById('user_date_naissance');
    if (dateNaissanceInput) {
        dateNaissanceInput.addEventListener('change', validateBirthDate);
    }

    // Gestion du rôle
    const roleSelect = document.getElementById('user_role');
    if (roleSelect) {
        roleSelect.addEventListener('change', validateRole);
    }

    // Gestion de la soumission du formulaire
    form.addEventListener('submit', handleSubmit);

    function validateField(input, config) {
        const value = input.value.trim();
        const isValid = config.regex.test(value);
        const isRequired = input.required;

        if (isRequired && value === '') {
            showError(input, 'Ce champ est obligatoire');
            return false;
        }

        if (!isValid && value !== '') {
            showError(input, config.error);
            return false;
        }

        clearError(input);
        return true;
    }

    function validateBirthDate() {
        const input = this;
        const birthDate = new Date(input.value);
        const today = new Date();
        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        
        if (birthDate > minAgeDate) {
            showError(input, 'Vous devez avoir au moins 18 ans');
            return false;
        }
        
        clearError(input);
        return true;
    }

    function validateRole() {
        const input = this;
        if (input.value === '') {
            showError(input, 'Veuillez sélectionner un rôle');
            return false;
        }
        clearError(input);
        return true;
    }

    function handleSubmit(e) {
        e.preventDefault();
        let isValid = true;

        // Validation de tous les champs
        Object.keys(fieldsConfig).forEach(field => {
            const input = document.getElementById(user_$,{field});
            if (input && !validateField(input, fieldsConfig[field])) isValid = false;
        });

        // Validation supplémentaire
        if (dateNaissanceInput && !validateBirthDate.call(dateNaissanceInput)) isValid = false;
        if (roleSelect && !validateRole.call(roleSelect)) isValid = false;

        if (isValid) {
            form.submit();
        } else {
            showGlobalError('Veuillez corriger les erreurs dans le formulaire');
        }
    }

    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorDiv = formGroup.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            formGroup.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // Ajout d'icône de statut
        const statusIcon = formGroup.querySelector('.input-status') || createStatusIcon(formGroup);
        statusIcon.className = 'input-status fas fa-times-circle text-danger';
    }

    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const errorDiv = formGroup.querySelector('.error-message');
        
        if (errorDiv) errorDiv.remove();
        
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        // Mise à jour de l'icône
        const statusIcon = formGroup.querySelector('.input-status');
        if (statusIcon) {
            statusIcon.className = 'input-status fas fa-check-circle text-success';
        }
    }

    function createStatusIcon(formGroup) {
        const icon = document.createElement('i');
        icon.className = 'input-status';
        icon.style.position = 'absolute';
        icon.style.right = '10px';
        icon.style.top = '38px';
        formGroup.style.position = 'relative';
        formGroup.appendChild(icon);
        return icon;
    }

    function showGlobalError(message) {
        let globalError = document.querySelector('.global-error');
        if (!globalError) {
            globalError = document.createElement('div');
            globalError.className = 'global-error alert alert-danger mt-3';
            form.prepend(globalError);
        }
        globalError.textContent = message;
    }
});