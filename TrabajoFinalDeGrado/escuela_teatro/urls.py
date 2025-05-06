from django.urls import path
from . import views

urlpatterns = [
    path('', views.inicio, name='inicio'),
    path('laTara', views.laTara, name='laTara'),
    path('blog', views.blog, name='blog'),
    path('contacto', views.contacto, name='contacto'),
    path('espectaculos', views.espectaculos, name='espectaculos'),
    path('educacion', views.educacion, name='educacion'),
    path('tienda', views.tienda, name='tienda'),
    
    path('login', views.login_view, name='login'),
    path('perfil/<int:user_id>/', views.perfil_view, name='perfil'),
    path('administracion/<int:id>/', views.admin_dashboard, name='administracion'),

    # URLs para Contacto
    #path('contactos/', views.ContactoListView.as_view(), name='contacto_list'),

    #URLs para Login
    path("login/", views.login_view, name="login"),
    #path("perfil/<int:id>/", views.perfil, name="perfil"),
    #path('registro/', views.registro, name='registro'),
    #path("login/", views.CustomLogoutView.as_view(), name="logout"),


]