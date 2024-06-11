PGDMP  !    &                |            mercado_soft_expert     16.3 (Ubuntu 16.3-1.pgdg22.04+1)     16.3 (Ubuntu 16.3-1.pgdg22.04+1) %    %           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            &           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            '           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            (           1262    24576    mercado_soft_expert    DATABASE     {   CREATE DATABASE mercado_soft_expert WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'C.UTF-8';
 #   DROP DATABASE mercado_soft_expert;
                postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                pg_database_owner    false            )           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                   pg_database_owner    false    4            �            1259    40961    pedidos    TABLE     �   CREATE TABLE public.pedidos (
    id bigint NOT NULL,
    valor_venda numeric NOT NULL,
    valor_venda_imposto numeric,
    data_cadastro timestamp without time zone NOT NULL,
    descricao character varying
);
    DROP TABLE public.pedidos;
       public         heap    postgres    false    4            �            1259    40960    pedidos_id_seq    SEQUENCE     w   CREATE SEQUENCE public.pedidos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.pedidos_id_seq;
       public          postgres    false    4    220            *           0    0    pedidos_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.pedidos_id_seq OWNED BY public.pedidos.id;
          public          postgres    false    219            �            1259    32795    produtos    TABLE     )  CREATE TABLE public.produtos (
    id bigint NOT NULL,
    nome character varying NOT NULL,
    preco_custo numeric NOT NULL,
    preco_venda numeric NOT NULL,
    quantidade integer NOT NULL,
    descricao text,
    tipo_produto integer,
    data_cadastro timestamp without time zone NOT NULL
);
    DROP TABLE public.produtos;
       public         heap    postgres    false    4            �            1259    32794    produtos_id_seq    SEQUENCE     x   CREATE SEQUENCE public.produtos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.produtos_id_seq;
       public          postgres    false    4    218            +           0    0    produtos_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.produtos_id_seq OWNED BY public.produtos.id;
          public          postgres    false    217            �            1259    40970    produtos_pedidos    TABLE     �   CREATE TABLE public.produtos_pedidos (
    id bigint NOT NULL,
    id_produto bigint NOT NULL,
    id_pedido bigint NOT NULL,
    quantidade integer,
    valor_unitario numeric,
    valor_total numeric,
    nome_produto character varying
);
 $   DROP TABLE public.produtos_pedidos;
       public         heap    postgres    false    4            �            1259    40969    produtos_pedidos_id_seq    SEQUENCE     �   CREATE SEQUENCE public.produtos_pedidos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.produtos_pedidos_id_seq;
       public          postgres    false    222    4            ,           0    0    produtos_pedidos_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.produtos_pedidos_id_seq OWNED BY public.produtos_pedidos.id;
          public          postgres    false    221            �            1259    32786    tipo_produto    TABLE     �   CREATE TABLE public.tipo_produto (
    id bigint NOT NULL,
    tipo character varying NOT NULL,
    valor_imposto character varying NOT NULL,
    data_cadastro timestamp without time zone
);
     DROP TABLE public.tipo_produto;
       public         heap    postgres    false    4            �            1259    32785    tipo_produto_id_seq    SEQUENCE     |   CREATE SEQUENCE public.tipo_produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.tipo_produto_id_seq;
       public          postgres    false    216    4            -           0    0    tipo_produto_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.tipo_produto_id_seq OWNED BY public.tipo_produto.id;
          public          postgres    false    215                       2604    40964 
   pedidos id    DEFAULT     h   ALTER TABLE ONLY public.pedidos ALTER COLUMN id SET DEFAULT nextval('public.pedidos_id_seq'::regclass);
 9   ALTER TABLE public.pedidos ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    220    219    220            ~           2604    32798    produtos id    DEFAULT     j   ALTER TABLE ONLY public.produtos ALTER COLUMN id SET DEFAULT nextval('public.produtos_id_seq'::regclass);
 :   ALTER TABLE public.produtos ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    217    218    218            �           2604    40973    produtos_pedidos id    DEFAULT     z   ALTER TABLE ONLY public.produtos_pedidos ALTER COLUMN id SET DEFAULT nextval('public.produtos_pedidos_id_seq'::regclass);
 B   ALTER TABLE public.produtos_pedidos ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    222    221    222            }           2604    32789    tipo_produto id    DEFAULT     r   ALTER TABLE ONLY public.tipo_produto ALTER COLUMN id SET DEFAULT nextval('public.tipo_produto_id_seq'::regclass);
 >   ALTER TABLE public.tipo_produto ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    215    216    216                       0    40961    pedidos 
   TABLE DATA           a   COPY public.pedidos (id, valor_venda, valor_venda_imposto, data_cadastro, descricao) FROM stdin;
    public          postgres    false    220   0*                 0    32795    produtos 
   TABLE DATA           z   COPY public.produtos (id, nome, preco_custo, preco_venda, quantidade, descricao, tipo_produto, data_cadastro) FROM stdin;
    public          postgres    false    218   �*       "          0    40970    produtos_pedidos 
   TABLE DATA           |   COPY public.produtos_pedidos (id, id_produto, id_pedido, quantidade, valor_unitario, valor_total, nome_produto) FROM stdin;
    public          postgres    false    222   -                 0    32786    tipo_produto 
   TABLE DATA           N   COPY public.tipo_produto (id, tipo, valor_imposto, data_cadastro) FROM stdin;
    public          postgres    false    216   �-       .           0    0    pedidos_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.pedidos_id_seq', 26, true);
          public          postgres    false    219            /           0    0    produtos_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.produtos_id_seq', 35, true);
          public          postgres    false    217            0           0    0    produtos_pedidos_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.produtos_pedidos_id_seq', 32, true);
          public          postgres    false    221            1           0    0    tipo_produto_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.tipo_produto_id_seq', 40, true);
          public          postgres    false    215            �           2606    40975 $   produtos_pedidos produtos_pedidos_pk 
   CONSTRAINT     b   ALTER TABLE ONLY public.produtos_pedidos
    ADD CONSTRAINT produtos_pedidos_pk PRIMARY KEY (id);
 N   ALTER TABLE ONLY public.produtos_pedidos DROP CONSTRAINT produtos_pedidos_pk;
       public            postgres    false    222            �           2606    32802    produtos produtos_pk 
   CONSTRAINT     R   ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_pk PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.produtos DROP CONSTRAINT produtos_pk;
       public            postgres    false    218            �           2606    32793    tipo_produto tipo_produto_pk 
   CONSTRAINT     Z   ALTER TABLE ONLY public.tipo_produto
    ADD CONSTRAINT tipo_produto_pk PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.tipo_produto DROP CONSTRAINT tipo_produto_pk;
       public            postgres    false    216            �           2606    40968    pedidos vendas_pk 
   CONSTRAINT     O   ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT vendas_pk PRIMARY KEY (id);
 ;   ALTER TABLE ONLY public.pedidos DROP CONSTRAINT vendas_pk;
       public            postgres    false    220            �           2606    40981 ,   produtos_pedidos produtos_pedidos_pedidos_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.produtos_pedidos
    ADD CONSTRAINT produtos_pedidos_pedidos_fk FOREIGN KEY (id_pedido) REFERENCES public.pedidos(id);
 V   ALTER TABLE ONLY public.produtos_pedidos DROP CONSTRAINT produtos_pedidos_pedidos_fk;
       public          postgres    false    3206    222    220            �           2606    40976 -   produtos_pedidos produtos_pedidos_produtos_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.produtos_pedidos
    ADD CONSTRAINT produtos_pedidos_produtos_fk FOREIGN KEY (id_produto) REFERENCES public.produtos(id);
 W   ALTER TABLE ONLY public.produtos_pedidos DROP CONSTRAINT produtos_pedidos_produtos_fk;
       public          postgres    false    222    218    3204            �           2606    32817 !   produtos produtos_tipo_produto_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.produtos
    ADD CONSTRAINT produtos_tipo_produto_fk FOREIGN KEY (tipo_produto) REFERENCES public.tipo_produto(id);
 K   ALTER TABLE ONLY public.produtos DROP CONSTRAINT produtos_tipo_produto_fk;
       public          postgres    false    218    216    3202                �   x�m�=�0���9E.P�I���L���R�6�8?-0U�_"0"j!�����*{M��n-���;�C6�B'+T���kxLGT2�|���½-�?��A�Y9�#�y�~�����7'��A���$pik��sZv�����1�         2  x�]R;n�@����H��#YT��VR0l)Ҍȑ�ɡ����Ha�p�#�b����QC����+2�Ck��LO�U�V�:K�V���e���3�h\�LO�AϪ(T��r���,]l�f�Z%E��ȡ��J��ȹ�p�,��N�W����M�Ι��5�<�`�#­�a�^`${ ��CI?�G�ls�LN.����9�/����H'7�̲�Q0"�Hӳ@�~�}0bw�!���{�F��-�i��S�s�V����	���1hL��It�E.�X�.�f���� �=.E7yt)�r�-�3alؙ[Z@�v�~��@1~?[�w���EO1��Ek����%?szi$��&������u���!k��7�l,�]Х��=�~�^��!0-a����&dz�g�D��V��2UU��埆pm�#�aO6�o���j��R��vt�V�n�C��6���������ok�K�~�!�;W��D��l��IQ�kI��~�*VUԴ��x����.�b�jr,J�m���H6��������E��H�$�*r=      "   �   x�}�;�0D��)8A��q��
�ЬC	��@����N$�-��͘؃�(KDu���6�ޯ۬�j0��","������s���G@,'�"i�i7%G�ʜ�}��v�n�4��\�iK�Ya�4��/dyF�<��'yWmh���gץp�} 8�:         �   x�eλ�0���z�,���쎂2�D�KI,�Q z6�b�R�O�9Dp����@�D�Z4��G�52�Х[���G�P�Np�BY�k�C�H���$�#]_��܌�Ғ˺�G�'�濯���½��4�g.�����p���ؕ3��i7h     