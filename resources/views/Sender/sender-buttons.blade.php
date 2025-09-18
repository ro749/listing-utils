<div style="display: flex; flex-direction:row; justify-content: center; gap:6px; margin-bottom: 1.5%; margin-top: 1%;">
    <button id="send-email-btn" style="display: flex; flex-direction:row; align-items: center; gap:6px;"  class="btn btn-light send-btn">
        <iconify-icon icon="{{ \Ro749\SharedUtils\Enums\Icon::SEND_MAIL->value }}"></iconify-icon>
        <span id="mail-tag">Correo</span>
    </button>
    <button id="send-whatsapp-btn" style="display: flex; flex-direction:row; align-items: center; gap:6px;"  class="btn btn-light send-btn">
        <iconify-icon icon="{{ \Ro749\SharedUtils\Enums\Icon::WHATSAPP->value }}"></iconify-icon>
        <span id="whatsapp-tag">Whatsapp</span>
    </button>
    <button  id="get-link-btn" style="display: flex; flex-direction:row; align-items: center; gap:6px;" class="btn btn-light send-btn">
        <iconify-icon icon="{{ \Ro749\SharedUtils\Enums\Icon::LINK->value }}"></iconify-icon>
        <span id="link-tag">Link</span>
    </button>
</div>
<x-shared-utils::modal id="ask-whatsapp-modal">
    @include('listing-utils::Sender.whatsapp-popup',["name" => $sender->client->name, "phone" => $sender->client->phone])
</x-shared-utils::modal>
<x-shared-utils::modal id="ask-link-modal">
    @include('listing-utils::Sender.link-popup',["name" => $sender->client->name, "phone" => $sender->client->phone])
</x-shared-utils::modal>
<x-shared-utils::modal id="show-link-modal">
    @include('listing-utils::Sender.copy-link-popup',["name" => $sender->client->name, "phone" => $sender->client->phone])
</x-shared-utils::modal>
<x-shared-utils::modal id="ask-mail-modal">
    @include('listing-utils::Sender.mail-popup',["name" => $sender->client->name, "mail" => $sender->client->mail])
</x-shared-utils::modal>
<x-shared-utils::modal id="sent-mail-modal">
    @include('listing-utils::Sender.sent-mail-popup',["name" => $sender->client->name, "mail" => $sender->client->mail])
</x-shared-utils::modal>
@push('scripts')
<script>
    $('#send-email-btn').on('click', function () {
        openPopup('ask-mail-modal');
    });
    $('#send-whatsapp-btn').on('click', function () {
        openPopup('ask-whatsapp-modal');
    });
    $('#get-link-btn').on('click', function () {
        openPopup('ask-link-modal');
    });
    $('#confirm-whatsapp').on('click', function () {
        $.ajax({
            url: 'sender/' + '{{ $sender->get_id() }}' + '/whatsapp',
            method: 'GET',
            dataType: 'text',
            data: {
                unit: {{ isset($unit) ? $unit->id : "selected_unit_id" }}
            },
            success: function (response) {
                window.open(response, '_blank');
                closePopup('ask-link-modal');
            }
        })
    });
    $('#confirm-mail').on('click', function () {
        $.ajax({
            url: 'sender/' + '{{ $sender->get_id() }}' + '/mail',
            method: 'GET',
            dataType: 'text',
            data: {
                unit: {{ isset($unit) ? $unit->id : "selected_unit_id" }}
            },
            success: function (response) {
                closePopup('ask-mail-modal');
                openPopup('sent-mail-modal',1000);
                
            }
        })
    });
    $('#confirm-link').on('click', function () {
        $.ajax({
            url: 'sender/' + '{{ $sender->get_id() }}' + '/link',
            method: 'GET',
            dataType: 'text',
            data: {
                unit: {{ isset($unit) ? $unit->id : "selected_unit_id" }}
            },
            success: function (response) {
                navigator.clipboard.writeText(response);
                $('#link').html(response);
                closePopup('ask-link-modal');
                openPopup('show-link-modal');
                
            }
        })
    });
</script>
@endpush