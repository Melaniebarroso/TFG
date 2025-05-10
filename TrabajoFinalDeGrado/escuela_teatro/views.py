from django.shortcuts import render


def inicio(request):
    return render(request, 'inicio/index.html')

def laTara(request):
    return render(request, 'laTara/index.html')

from .models import BlogPost

def blog(request):
    posts = BlogPost.objects.order_by('-fecha_creacion')
    return render(request, 'blog/index.html', {'posts': posts})
from django.shortcuts import get_object_or_404
from .models import BlogPost

def post_detalle(request, pk):
    post = get_object_or_404(BlogPost, pk=pk)
    return render(request, 'blog/detalle.html', {'post': post})

def contacto(request):
    return render(request, 'contacto/index.html')

def educacion(request):
    return render(request, 'educacion/index.html')

def espectaculos(request):
    return render(request, 'espectaculos/index.html')

def tienda(request):
    return render(request, 'tienda/index.html')

from django.shortcuts import redirect, render
from .models import Administrador, Alumno
from django.urls import reverse

from django.urls import reverse

def login_view(request):
    if request.method == "POST":
        correo = request.POST.get('correo')
        password = request.POST.get('password')
        
        try:
            admin = Administrador.objects.get(correo=correo, password=password)
            request.session['user_id'] = admin.id
            request.session['tipo'] = 'admin'
            return redirect(reverse('administracion', kwargs={'id': admin.id}))
        except Administrador.DoesNotExist:
            pass

        try:
            alumno = Alumno.objects.get(email=correo, password=password)
            request.session['user_id'] = alumno.id
            request.session['tipo'] = 'alumno'
            return redirect('perfil', user_id=alumno.id)
        except Alumno.DoesNotExist:
            pass

        return render(request, 'login/index.html', {'error': 'Correo o contraseña incorrectos'})

    return render(request, 'login/index.html')


from django.shortcuts import render, redirect
from .models import Administrador, Alumno
from django.shortcuts import render, redirect
from .models import Administrador, Alumno, BlogPost
from .forms import BlogPostForm

def admin_dashboard(request, id):
    try:
        admin = Administrador.objects.get(id=id)
    except Administrador.DoesNotExist:
        return redirect('login')

    total_alumnos = Alumno.objects.count()
    objetivo_alumnos = 50
    porcentaje = (total_alumnos / objetivo_alumnos) * 100 if objetivo_alumnos > 0 else 0

    if request.method == 'POST':
        form = BlogPostForm(request.POST, request.FILES)
        if form.is_valid():
            form.save()
            return redirect('administracion', id=id) 
    else:
        form = BlogPostForm()

    posts = BlogPost.objects.order_by('-fecha_creacion')

    contexto = {
        'admin': admin,
        'total_alumnos': total_alumnos,
        'porcentaje_alumnos': porcentaje,
        'form': form,
        'posts': posts,
    }

    return render(request, 'administracion/index.html', contexto)



def perfil_view(request, user_id):
    if request.session.get('tipo') != 'alumno' or request.session.get('user_id') != user_id:
        return redirect('login')

    try:
        alumno = Alumno.objects.get(id=user_id)
    except Alumno.DoesNotExist:
        return redirect('login')
    return render(request, 'perfil/index.html', {'alumno': alumno})


from django.shortcuts import render, redirect
from .forms import BlogPostForm

def crear_post(request):
    if request.method == 'POST':
        form = BlogPostForm(request.POST, request.FILES)
        if form.is_valid():
            form.save()
            return redirect('panel_admin')
    else:
        form = BlogPostForm()
    return render(request, 'panel_admin/crear_post.html', {'form': form})
