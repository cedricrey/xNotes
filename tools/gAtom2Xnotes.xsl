<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:xalan="http://xml.apache.org/xalan"
  exclude-result-prefixes="atom dc xalan"
  >
    <xsl:output method="xml" indent="yes" encoding="UTF-8" xalan:indent-amount="2"
    doctype-public="-//W3C//DTD XML 1.00//EN"
	doctype-system="../../../dtd/xnotes.dtd"/>
    
    <xsl:template match="/atom:feed">
        <notebook>
			<xsl:attribute name="title"><xsl:value-of select="atom:title"/></xsl:attribute>
			<xsl:attribute name="modified"><xsl:value-of select="atom:updated"/></xsl:attribute>			
        	<xsl:apply-templates select="atom:entry"/>
        </notebook>    	
    </xsl:template>
    
    
    <xsl:template match="atom:entry">   		
    	<xsl:choose>
	    	<xsl:when test="atom:content = ''">
	    		<xsl:call-template name="contenuSection"/>
	    	</xsl:when>
    	</xsl:choose>
    </xsl:template>
    
     <xsl:template name="contenuSection">
		<xsl:variable name="id" select="atom:id"/>
		<xsl:choose>
		<xsl:when test="atom:title != ''">
			<section>
				<xsl:attribute name="title"><xsl:value-of select="atom:title"/></xsl:attribute>
				<xsl:attribute name="modified"><xsl:value-of select="atom:updated"/></xsl:attribute>
				    	<xsl:call-template name="contenuNote">
				    		<xsl:with-param name="id" select="$id"/>
				    	</xsl:call-template>
			</section>
		</xsl:when>
		<xsl:otherwise>
			<xsl:call-template name="contenuNote">
	    		<xsl:with-param name="id" select="$id"/>
	    	</xsl:call-template>
		</xsl:otherwise>
		</xsl:choose>
		<xsl:text>
			
		</xsl:text>		
     </xsl:template>
     
     
     <xsl:template name="contenuNote">
     <xsl:param name="id"/>		
     		<xsl:for-each select="../atom:entry[atom:category/@term = $id]">
				<note>
					<xsl:attribute name="modified"><xsl:value-of select="atom:updated"/></xsl:attribute>
					<xsl:choose>
						<xsl:when test="normalize-space(atom:content) = ' '">
							<xsl:attribute name="type">link</xsl:attribute>
							<xsl:attribute name="title">
								<xsl:value-of select="atom:title"/>
							</xsl:attribute>
							<xsl:value-of select="atom:link/@href"/>
						</xsl:when>
						<xsl:otherwise>			
							<xsl:attribute name="type">html</xsl:attribute>
							<xsl:value-of select="atom:content"/>
						</xsl:otherwise>
					</xsl:choose>
				</note>
			</xsl:for-each>
     </xsl:template>
     
</xsl:stylesheet>
