USE [totem]
GO
/****** Object:  Table [dbo].[alunos]    Script Date: 02/08/2018 00:01:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[alunos](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[nome] [varchar](255) NOT NULL,
	[email] [varchar](255) NOT NULL,
	[telefone] [varchar](16) NOT NULL,
	[matricula] [varchar](40) NOT NULL,
	[curso] [int] NOT NULL,
	[faculdade] [int] NOT NULL,
 CONSTRAINT [PK_alunos] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [MATRICULA] UNIQUE NONCLUSTERED 
(
	[matricula] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[cursos]    Script Date: 02/08/2018 00:01:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[cursos](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[area] [varchar](50) NOT NULL,
	[nome] [varchar](100) NOT NULL,
	[faculdade] [int] NOT NULL,
 CONSTRAINT [PK_cursos] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[eventos]    Script Date: 02/08/2018 00:01:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[eventos](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[nome] [varchar](255) NOT NULL,
	[faculdade] [int] NOT NULL,
	[data] [date] NOT NULL,
	[inicio] [time](7) NOT NULL,
	[final] [time](7) NOT NULL,
 CONSTRAINT [PK_eventos] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[instituicao]    Script Date: 02/08/2018 00:01:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[instituicao](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[endereco] [varchar](150) NOT NULL,
	[nome_faculdade] [varchar](100) NOT NULL,
 CONSTRAINT [PK_instituicao] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[alunos]  WITH CHECK ADD  CONSTRAINT [FK_alunos_cursos] FOREIGN KEY([curso])
REFERENCES [dbo].[cursos] ([id])
GO
ALTER TABLE [dbo].[alunos] CHECK CONSTRAINT [FK_alunos_cursos]
GO
ALTER TABLE [dbo].[alunos]  WITH CHECK ADD  CONSTRAINT [FK_alunos_instituicao] FOREIGN KEY([faculdade])
REFERENCES [dbo].[instituicao] ([id])
GO
ALTER TABLE [dbo].[alunos] CHECK CONSTRAINT [FK_alunos_instituicao]
GO
ALTER TABLE [dbo].[cursos]  WITH CHECK ADD  CONSTRAINT [FK_cursos_instituicao] FOREIGN KEY([faculdade])
REFERENCES [dbo].[instituicao] ([id])
GO
ALTER TABLE [dbo].[cursos] CHECK CONSTRAINT [FK_cursos_instituicao]
GO
ALTER TABLE [dbo].[eventos]  WITH CHECK ADD  CONSTRAINT [FK_eventos_instituicao] FOREIGN KEY([faculdade])
REFERENCES [dbo].[instituicao] ([id])
GO
ALTER TABLE [dbo].[eventos] CHECK CONSTRAINT [FK_eventos_instituicao]
GO
