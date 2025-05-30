from django.shortcuts import get_object_or_404
from django.shortcuts import redirect, render
from .models import Administrador, Alumno, Pedido, Inscripcion, MaterialCurso, DetallePedido, Producto, Curso, Espectaculo, BlogPost, ContactoFormulario
from django.urls import reverse
from .forms import BlogPostForm, PedidoForm, ContactForm, AlumnoForm
from django.template.loader import render_to_string
from weasyprint import HTML
from django.http import HttpResponse
from io import BytesIO
from django.utils import timezone
from django.db.models import Count, Sum, F, FloatField
from django.utils.timezone import now
from django.db.models.functions import TruncMonth
from datetime import datetime
from dateutil.relativedelta import relativedelta
from django.contrib.auth.views import LogoutView
from django.urls import reverse_lazy
from django.core.mail import send_mail
from django.contrib import messages
from .models import ContactoNewsletter
from .forms import ContactoNewsletterForm
from decimal import Decimal
from django.shortcuts import render
from .models import Producto
from django.shortcuts import render, redirect
from django.core.mail import send_mail
from django.contrib import messages
from django.conf import settings
from .models import ContactoNewsletter
from .forms import ContactoNewsletterForm

def inicio(request):
    posts = BlogPost.objects.order_by('-fecha_creacion')[:5]

    if request.method == 'POST':
        form = ContactoNewsletterForm(request.POST)
        if form.is_valid():
            contacto = form.save()

            send_mail(
                subject='📥 Nueva suscripción a la newsletter',
                message=f'Se ha suscrito:\n\nNombre: {contacto.nombre}\nEmail: {contacto.email}',
                from_email=settings.DEFAULT_FROM_EMAIL,
                recipient_list=[settings.CONTACT_EMAIL],
                fail_silently=False,
            )
            #Enviamos email tanto a la persona que se ha suscrito como al administrador para que vea quién ha sido
            send_mail(
                subject='🎉 ¡Gracias por suscribirte! Aquí tienes tu cupón exclusivo',
                message=(
                    f"Hola {contacto.nombre},\n\n"
                    f"¡Gracias por unirte a nuestra newsletter! 🎭\n"
                    f"Aquí tienes tu cupón exclusivo de bienvenida:\n\n"
                    f"👉 CÓDIGO: BIENVENIDO10\n"
                    f"📦 Valor: 10% de descuento en tu primera compra\n"
                    f"🕒 Válido hasta: 31/12/2025\n\n"
                    f"¡Esperamos que lo disfrutes!\n\n"
                    f"El equipo de Teatro ✨"
                ),
                from_email=settings.DEFAULT_FROM_EMAIL,
                recipient_list=[contacto.email],
                fail_silently=False,
            )

            messages.success(request, '¡Gracias por suscribirte! Revisa tu correo, te hemos enviado un cupón. 🎁')
            return redirect('inicio')
    else:
        form = ContactoNewsletterForm()

    return render(request, 'inicio/index.html', {
        'posts': posts,
        'form': form,
    })

def blog(request):
    posts = BlogPost.objects.order_by('-fecha_creacion')
    return render(request, 'blog/index.html', {'posts': posts})

def post_detalle(request, pk):
    post = get_object_or_404(BlogPost, pk=pk)
    return render(request, 'blog/detalle.html', {'post': post})


def contacto(request):
    if request.method == 'POST':
        form = ContactForm(request.POST)
        if form.is_valid():
            nombre = form.cleaned_data['nombre']
            email = form.cleaned_data['email']
            mensaje = form.cleaned_data['mensaje']

            contacto = ContactoFormulario(
                nombre=nombre,
                email=email,
                mensaje=mensaje,
                fecha_envio=timezone.now()
            )
            contacto.save()

            # Lo mismo que en el otro formulario, mensaje para los dos
            mensaje_completo = f"De: {nombre} <{email}>\n\nMensaje 👉 {mensaje}"

            send_mail(
                f"Nueva entrada en el formulario. Email: {email}",
                mensaje_completo,
                settings.EMAIL_HOST_USER,
                [settings.CONTACT_EMAIL],
                fail_silently=False,
            )

            asunto_usuario = "Gracias por contactar con nosotros"
            mensaje_usuario = (
                f"Hola {nombre},\n\n"
                "Gracias por ponerse en contacto con nosotros. "
                "Le atenderemos lo antes posible.\n\n"
                "Nuestra dirección es:\n"
                "C/ Juliana 6, B Granada\n\n"
                "Un saludo,\n"
                "El equipo de La Tará"
            )

            send_mail(
                asunto_usuario,
                mensaje_usuario,
                settings.EMAIL_HOST_USER,
                [email],
                fail_silently=False,
            )

    else:
        form = ContactForm()

    return render(request, 'contacto/index.html', {'form': form})


def educacion(request):
    cursos = Curso.objects.all()
    return render(request, 'educacion/index.html', {'cursos': cursos})

def espectaculos(request):
    context = {
        'obras': Espectaculo.objects.filter(categoria='obras'),
        'cuentacuentos': Espectaculo.objects.filter(categoria='cuentacuentos'),
        'animaciones': Espectaculo.objects.filter(categoria='animaciones'),
        'talleres': Espectaculo.objects.filter(categoria='talleres'),
    }
    return render(request, 'espectaculos/index.html', context)

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
    total = Decimal('0.00')

    for producto_id, cantidad in carrito.items():
        try:
            producto = Producto.objects.get(id=producto_id)
            subtotal = producto.precio * cantidad
            total += subtotal
            productos.append({
                'producto': producto,
                'cantidad': cantidad,
                'subtotal': subtotal
            })
        except Producto.DoesNotExist:
            continue

    descuento_pct = 0
    error_codigo = ''
    total_con_descuento = total
    codigo_descuento = ''

    if request.method == 'POST':
        codigo_descuento = request.POST.get('codigo_descuento', '').strip().upper()

        codigos_validos = {
            'BIENVENIDO10': 10,  # 10% y 5 de descuento
            'DESC5': 5,
        }

        if codigo_descuento in codigos_validos:
            descuento_pct = codigos_validos[codigo_descuento]
            porcentaje_decimal = Decimal(descuento_pct) / Decimal('100')
            descuento_valor = (total * porcentaje_decimal).quantize(Decimal('0.01'))
            total_con_descuento = total - descuento_valor
            # Si refrescan o cambian de página se guarda el código en el carrito
            request.session['codigo_descuento'] = codigo_descuento
            request.session['descuento_pct'] = descuento_pct
        else:
            error_codigo = 'Código de descuento inválido o expirado.'
            request.session.pop('codigo_descuento', None)
            request.session.pop('descuento_pct', None)

    else:
        descuento_pct = request.session.get('descuento_pct', 0)
        codigo_descuento = request.session.get('codigo_descuento', '')
        if descuento_pct > 0:
            porcentaje_decimal = Decimal(descuento_pct) / Decimal('100')
            descuento_valor = (total * porcentaje_decimal).quantize(Decimal('0.01'))
            total_con_descuento = total - descuento_valor

    context = {
        'productos_carrito': productos,
        'total': total,
        'descuento_pct': descuento_pct,
        'descuento': descuento_valor if descuento_pct > 0 else None,
        'total_con_descuento': total_con_descuento,
        'error_codigo': error_codigo,
        'codigo_descuento': codigo_descuento,
    }


    return render(request, 'tienda/carrito.html', context)


def eliminar_del_carrito(request, producto_id):
    carrito = request.session.get('carrito', {})
    producto_id_str = str(producto_id)
    if producto_id_str in carrito:
        del carrito[producto_id_str]
        request.session['carrito'] = carrito
    return redirect('ver_carrito')

#Vista para rellenar los datos de la compra. Pasarela de pago en proceso en trámites bancarios.
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
                    precio_unitario=producto.precio_unitario
                )
            request.session['carrito'] = {}
            messages.success(request, "Pedido realizado con éxito. ¡Gracias por tu compra!")
            return redirect('tienda')
    else:
        form = PedidoForm()

    return render(request, 'tienda/checkout.html', {'form': form})

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

# ------------------------------------------------Vista para el panel de administración ---------------------------------------------------------
# ------------------------------------------------------------------------------------------------------------------------------------------------
def admin_dashboard(request, id):
    try:
        admin = Administrador.objects.get(id=id)
    except Administrador.DoesNotExist:
        return redirect('login')

    total_alumnos = Alumno.objects.count()
    objetivo_alumnos = 50
    porcentaje = (total_alumnos / objetivo_alumnos) * 100 if objetivo_alumnos > 0 else 0
    porcentaje_restante = 100 - porcentaje

    cursos = Curso.objects.prefetch_related('inscripcion_set', 'materiales')

    alumnos = Alumno.objects.all()
    pedidos = Pedido.objects.all().order_by('-fecha')
    posts = BlogPost.objects.order_by('-fecha_creacion')

    ingresos_totales = DetallePedido.objects.aggregate(
        total=Sum(F('precio_unitario') * F('cantidad'), output_field=FloatField())
    )['total'] or 0

    pedidos_pendientes = pedidos.filter(estado='pendiente').count()
    cursos_activos = cursos.count()
    materiales_subidos = sum(curso.materiales.count() for curso in cursos)

    seis_meses_atras = now().date().replace(day=1) - relativedelta(months=5)

    inscripciones_por_mes = (
        Inscripcion.objects
        .filter(fecha_inscripcion__gte=seis_meses_atras)
        .annotate(mes=TruncMonth('fecha_inscripcion'))
        .values('mes')
        .annotate(cantidad=Count('id'))
        .order_by('mes')
    )

    meses = []
    cantidades = []
    for i in range(6):
        mes_actual = seis_meses_atras + relativedelta(months=i)
        meses.append(mes_actual.strftime("%b %Y"))
        inscripcion_mes = next((item for item in inscripciones_por_mes if item['mes'].date() == mes_actual), None)
        cantidades.append(inscripcion_mes['cantidad'] if inscripcion_mes else 0)

    if request.method == 'POST':
        if 'crear_alumno' in request.POST:
            alumno_form = AlumnoForm(request.POST)
            form = BlogPostForm()  # Evitamos conflicto mostrando otro formulario vacío
            if alumno_form.is_valid():
                alumno_form.save()
                return redirect('administracion', id=id)
        else:
            form = BlogPostForm(request.POST, request.FILES)
            alumno_form = AlumnoForm()  # Otro formulario vacío
            if form.is_valid():
                form.save()
                return redirect('administracion', id=id)
    else:
        form = BlogPostForm()
        alumno_form = AlumnoForm()

    contexto = {
        'admin': admin,
        'total_alumnos': total_alumnos,
        'porcentaje_alumnos': round(porcentaje, 2),
        'porcentaje_restante': round(porcentaje_restante, 2),

        'form': form,
        'alumno_form': alumno_form,

        'cursos': cursos,
        'alumnos': alumnos,
        'pedidos': pedidos,
        'posts': posts,

        'ingresos_totales': ingresos_totales,
        'pedidos_pendientes': pedidos_pendientes,
        'cursos_activos': cursos_activos,
        'materiales_subidos': materiales_subidos,

        'meses_inscripciones': meses,
        'cantidades_inscripciones': cantidades,
    }

    return render(request, 'administracion/index.html', contexto)


def detalle_pedido(request, id, pedido_id):
    pedido = get_object_or_404(Pedido, id=pedido_id)
    detalles = pedido.detallepedido_set.all()

    for detalle in detalles:
        detalle.subtotal = detalle.precio_unitario * detalle.cantidad

    return render(request, 'tienda/detalle_pedido.html', {
        'pedido': pedido,
        'detalles': detalles,
    })
from django.shortcuts import get_object_or_404, redirect
from django.contrib import messages

def eliminar_inscripcion(request, admin_id, inscripcion_id):
    if request.method == 'POST':
        inscripcion = get_object_or_404(Inscripcion, id=inscripcion_id)
        inscripcion.delete()
        messages.success(request, f"Inscripción eliminada correctamente.")
    return redirect('administracion', id=admin_id)

def eliminar_alumno(request, admin_id, alumno_id):
    if request.method == 'POST':
        alumno = get_object_or_404(Alumno, id=alumno_id)
        alumno.delete()
        messages.success(request, f"Alumno eliminado correctamente.")
    return redirect('administracion', id=admin_id)


def crear_post(request):
    if request.method == 'POST':
        form = BlogPostForm(request.POST, request.FILES)
        if form.is_valid():
            form.save()
            return redirect('panel_admin')
    else:
        form = BlogPostForm()
    return render(request, 'panel_admin/crear_post.html', {'form': form})

def subir_material(request, admin_id, curso_id):
    curso = get_object_or_404(Curso, id=curso_id)
    titulo = request.POST.get('titulo')
    archivo = request.FILES.get('archivo')

    if titulo and archivo:
        MaterialCurso.objects.create(curso=curso, titulo=titulo, archivo=archivo)
    return redirect('administracion', id=admin_id)

class CustomLogoutView(LogoutView):
    next_page = reverse_lazy('inicio') 

    def dispatch(self, request, *args, **kwargs):
        request.session.flush()
        return super().dispatch(request, *args, **kwargs)


def generar_factura_pdf(request):
    if request.method == 'POST':
        cliente_nombre = request.POST.get('cliente_nombre')
        cliente_direccion = request.POST.get('cliente_direccion')
        cliente_nif = request.POST.get('cliente_nif')
        cliente_email = request.POST.get('cliente_email')
        cliente_telefono = request.POST.get('cliente_telefono')
        concepto = request.POST.get('concepto')
        base = float(request.POST.get('base', 0))
        iva = float(request.POST.get('iva', 0))
        irpf = float(request.POST.get('irpf', 0))

        iva_importe = base * (iva / 100)
        subtotal = base + iva_importe
        irpf_importe = subtotal * (irpf / 100)
        total = subtotal - irpf_importe


        # contexto con todos los datos que nos hacen falta para la factura
        html_string = render_to_string('administracion/modeloFactura.html', {
            'cliente_nombre': cliente_nombre,
            'cliente_direccion': cliente_direccion,
            'cliente_nif': cliente_nif,
            'cliente_email': cliente_email,
            'cliente_telefono': cliente_telefono,

            'concepto': concepto,
            'base': f"{base:.2f}",
            'iva': iva,
            'iva_importe': f"{iva_importe:.2f}",
            'irpf': irpf,
            'irpf_importe': f"{irpf_importe:.2f}",
            'total': f"{total:.2f}",
        })

        # Generar PDF
        pdf_file = BytesIO()
        HTML(string=html_string).write_pdf(pdf_file)
        pdf_file.seek(0)

        response = HttpResponse(pdf_file, content_type='application/pdf')
        response['Content-Disposition'] = 'inline; filename="factura.pdf"'
        return response

    return HttpResponse("Método no permitido", status=405)

from django.utils import timezone
from django.shortcuts import get_object_or_404
from django.contrib import messages

def inscribir_alumno(request, admin_id, curso_id):
    if request.method == 'POST':
        alumno_id = request.POST.get('alumno_id')
        curso = get_object_or_404(Curso, id=curso_id)
        alumno = get_object_or_404(Alumno, id=alumno_id)

        existe = Inscripcion.objects.filter(curso=curso, alumno=alumno).exists()
        if existe:
            messages.warning(request, f"{alumno} ya está inscrito en {curso}.")
        else:
            Inscripcion.objects.create(
                alumno=alumno,
                curso=curso,
                fecha_inscripcion=timezone.now(),
                estado='activo' 
            )
            messages.success(request, f"{alumno} inscrito exitosamente en {curso}.")

    return redirect('administracion', id=admin_id)


# ------------------------------------Vista para la sección del perfil de alumno ---------------------------------------------------

def perfil_view(request, user_id):
    if request.session.get('tipo') != 'alumno' or request.session.get('user_id') != user_id:
        return redirect('login')

    try:
        alumno = Alumno.objects.get(id=user_id)
    except Alumno.DoesNotExist:
        return redirect('login')

    inscripciones = Inscripcion.objects.filter(alumno=alumno).select_related('curso')

    return render(request, 'perfil/index.html', {
        'alumno': alumno,
        'inscripciones': inscripciones,
    })
