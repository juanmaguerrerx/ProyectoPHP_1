<style>
    .m{
        margin-right: 2vh;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">BungleBuilding S.L</a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <!-- Aquí puedes agregar otros elementos del menú si es necesario -->
                {{-- @if () --}}
                <li class="nav-item">
                    <button type="button" class="btn btn-success m">Añadir Tarea</button>
                </li>
                {{-- @endif --}}
            <li class="nav-item">
                <a href="#"><button type="button" class="btn btn-outline-primary">Cerrar Sesión</button></a>
            </li>
        </ul>
    </div>
</nav>

