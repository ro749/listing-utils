<div class="sender-popup">
    <div style="text-align:center">
        Abra Whatsapp para enviar el cotización al cliente.
    </div>
    <div class="row" style="justify-content: center; margin:0px;">
        <a style="display: flex; flex-direction: row; justify-content: center; margin:0px;" href="https://wa.me/{{ $sender->client->phone }}" target="_blank">
            <button class="btn btn-success btn-round btn-block mb-3" style="width: 40%; margin-top: 6px; margin-left: auto; margin-right: auto;" type="button">
                Abrir Whatsapp
            </button>
        </a>
    </div>
</div>