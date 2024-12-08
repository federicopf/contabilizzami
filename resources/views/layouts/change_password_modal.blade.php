<!-- Modal Modifica Password -->
<div class="modal fade" id="passwordEditModal" tabindex="-1" aria-labelledby="passwordEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordEditModalLabel">Modifica Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordEditForm" action="{{ route('profile.password.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="newPassword" class="form-label">Nuova Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="password" placeholder="Inserisci la nuova password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#newPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="confirmPassword" class="form-label">Conferma Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Conferma la nuova password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#confirmPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salva</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.toggle-password').on('click', function () {
            const isPasswordVisible = $(this).find('i').hasClass('bi-eye-slash');

            // Trova tutti i campi password e i bottoni toggle
            const inputs = $('#newPassword, #confirmPassword');
            const icons = $('.toggle-password i');

            if (isPasswordVisible) {
                // Nascondi le password
                inputs.attr('type', 'password');
                icons.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                // Mostra le password
                inputs.attr('type', 'text');
                icons.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });
    });
</script>