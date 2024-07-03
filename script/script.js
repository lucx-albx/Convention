const API_ISCRIZIONE = "../iscrizione_speech.php"
const API_DISISCRIZIONE = "../disiscrizione_speech.php"
const API_ELIMINA_SPEECH = "../elimina_speech.php"

const iscriviti_specch =(id_prog, nome_sala)=>{
    let idp = id_prog
    let ns = nome_sala
    
    fetch(API_ISCRIZIONE, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({idp, ns})
    })
    .then(testo=>testo.json())
    .then((data)=>{
        Swal.fire({
            title: 'Iscritto',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.reload()
        })
    })
}

const disiscriviti_speech =(id_prog, nome_sala)=>{
    let idp = id_prog
    let ns = nome_sala
    
    fetch(API_DISISCRIZIONE, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({idp, ns})
    })
    .then(testo=>testo.json())
    .then((data)=>{
        Swal.fire({
            title: 'Disiscritto',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.reload()
        })
    })
}

const elimina_specch =(id_speech)=>{
    let ids = id_speech
    
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Sei davvero sicuro di voler eliminare questo speech?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminalo!',
        cancelButtonText: 'No, annulla!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(API_ELIMINA_SPEECH, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ids})
            })
            .then(testo=>testo.json())
            .then((data)=>{
                Swal.fire({
                    title: 'Eliminato',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload()
                })
            })
        }
    })
}