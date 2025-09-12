<div style="display: flex; flex-direction:row; justify-content: center; gap:6px; margin-bottom: 1.5%; margin-top: 1%;">
    <button style="display: flex; flex-direction:row; align-items: center; gap:6px;" onclick="openPopup('ask-mail-Modal')" class="btn btn-light send-btn" id="send-Email-Btn">
        <iconify-icon icon="{{ \Ro749\SharedUtils\Enums\Icon::SEND_MAIL->value }}"></iconify-icon>
        <span id="mail-tag">Enviar Correo</span>
    </button>
    <button style="display: flex; flex-direction:row; align-items: center; gap:6px;" onclick="openPopup('ask-link-Modal')" class="btn btn-light send-btn" id="send-Link-Btn">
        <iconify-icon icon="{{ \Ro749\SharedUtils\Enums\Icon::LINK->value }}"></iconify-icon>
        <span id="link-tag">Whatsapp</span>
    </button>
</div>
<x-shared-utils::modal id="ask-link-Modal">
    @include('listing-utils::Sender.link-popup',["name" => $sender->client->name, "phone" => $sender->client->phone])
</x-shared-utils::modal>
<x-shared-utils::modal id="ask-mail-Modal">
    @include('listing-utils::Sender.mail-popup',["name" => $sender->client->name, "mail" => $sender->client->mail])
</x-shared-utils::modal>
<x-shared-utils::modal id="sent-mail-Modal">
    @include('listing-utils::Sender.sent-mail-popup',["name" => $sender->client->name, "mail" => $sender->client->mail])
</x-shared-utils::modal>
@push('scripts')
<script>
    $('#send-link-btn').on('click', function () {
        $.ajax({
            url: 'sender/' + '{{ $sender->id }}' + '/link',
            method: 'GET',
            dataType: 'text',
            data: {
                unit: {{ isset($unit) ? $unit->id : "selected_unit_id" }}
            },
            success: function (response) {
                window.open(response, '_blank');
                closePopup('ask-link-Modal');
            }
        })
    });
    $('#send-mail-btn').on('click', function () {
        $.ajax({
            url: 'sender/' + '{{ $sender->id }}' + '/mail',
            method: 'GET',
            dataType: 'text',
            data: {
                unit: {{ isset($unit) ? $unit->id : "selected_unit_id" }}
            },
            success: function (response) {
                closePopup('ask-mail-Modal');
                openPopup('sent-mail-Modal',500);
                
            }
        })
    })
</script>
@endpush