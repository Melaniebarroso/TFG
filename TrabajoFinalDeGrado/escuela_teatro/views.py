from django.shortcuts import render
from django.shortcuts import get_object_or_404
from .models import BlogPost
from django.shortcuts import redirect, render
from .models import Administrador, Alumno
from django.urls import reverse
from .forms import BlogPostForm
from .models import Producto


def inicio(request):
    posts = BlogPost.objects.order_by('-fecha_creacion')[:5] 
    return render(request, 'inicio/index.html', {'posts': posts})

def laTara(request):
    return render(request, 'laTara/index.html')

from .models import BlogPost

def blog(request):
    posts = BlogPost.objects.order_by('-fecha_creacion')
    return render(request, 'blog/index.html', {'posts': posts})

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
    productos = Producto.objects.all()
    return render(request, 'tienda/index.html', {'productos': productos})

def agregar_al_carrito(request, producto_id):
    carrito = request.session.get('carrito', {})
    carrito[str(producto_id)] = carrito.get(str(producto_id), 0) + 1
    request.session['carrito'] = carrito
    return redirect('tienda')


def ver_carrito(request):
    carrito = request.session.get('carrito', {})
    productos = []
    total = 0

    for producto_id, cantidad in carrito.items():
        producto = Producto.objects.get(id=producto_id)
        subtotal = producto.precio * cantidad
        total += subtotal
        productos.append({
            'producto': producto,
            'cantidad': cantidad,
            'subtotal': subtotal
        })

    context = {
        'productos_carrito': productos,
        'total': total
    }
    return render(request, 'tienda/carrito.html', context)

def eliminar_del_carrito(request, producto_id):
    carrito = request.session.get('carrito', {})
    producto_id_str = str(producto_id)
    if producto_id_str in carrito:
        del carrito[producto_id_str]
        request.session['carrito'] = carrito
    return redirect('ver_carrito')

from django.utils import timezone
from .forms import PedidoForm
from .models import Pedido, DetallePedido, Producto
from django.contrib import messages

def checkout(request):
    carrito = request.session.get('carrito', {})
    if not carrito:
        messages.warning(request, "Tu carrito está vacío.")
        return redirect('tienda')

    if request.method == 'POST':
        form = PedidoForm(request.POST)
        if form.is_valid():
            pedido = Pedido.objects.create(
                nombre_cliente=form.cleaned_data['nombre_cliente'],
                email_cliente=form.cleaned_data['email_cliente'],
                direccion_envio=form.cleaned_data['direccion_envio'],
                fecha=timezone.now(),
                estado='Pendiente'
            )
            for producto_id, cantidad in carrito.items():
                producto = Producto.objects.get(id=producto_id)
                DetallePedido.objects.create(
                    pedido=pedido,
                    producto=producto,
                    cantidad=cantidad,
                    precio_unitario=producto.precio
                )
            request.session['carrito'] = {}
            messages.success(request, "Pedido realizado con éxito. ¡Gracias por tu compra!")
            return redirect('tienda')
    else:
        form = PedidoForm()

    return render(request, 'tienda/checkout.html', {'form': form})

# Esto será solo para el admin
def lista_pedidos(request):
    pedidos = Pedido.objects.all().order_by('-fecha')
    return render(request, 'tienda/pedidos.html', {'pedidos': pedidos})
from django.shortcuts import get_object_or_404

def detalle_pedido(request, pedido_id):
    pedido = get_object_or_404(Pedido, id=pedido_id)
    detalles = pedido.detallepedido_set.all() 
    return render(request, 'tienda/detalle_pedido.html', {'pedido': pedido, 'detalles': detalles})




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
