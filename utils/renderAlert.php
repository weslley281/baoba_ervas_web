<?php
// Função para renderizar alertas Bootstrap
function renderAlert($type, $strongText, $message)
{
    return "
        <div class='mt-5 alert alert-$type alert-dismissible fade show' role='alert'>
            <strong>$strongText</strong> $message
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
    ";
}
