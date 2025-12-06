#----------------------------------------------------------------------
#
# Filename    : Makefile
# Written By  : Reiot
#
#----------------------------------------------------------------------

#----------------------------------------------------------------------
# Directories Setting
#----------------------------------------------------------------------
VSHOME    = ..
BINDIR    = $(VSHOME)/bin
LIBDIR    = $(VSHOME)/lib
LOGDIR    = $(VSHOME)/log
TEMPDIR   = $(VSHOME)/temp

#----------------------------------------------------------------------
# actions
#----------------------------------------------------------------------
# Build all servers sequentially (for low-performance systems)
# Core libraries are built once first, then each server builds separately
# This prevents multiple processes from compiling shared files simultaneously
all : corelib step1 step2 step3

# Build shared Core library first (used by all servers)
# This ensures shared components are compiled only once, reducing RAM usage
corelib :
	$(MAKE) -C Core core

# Step 1: Build gameserver (only server-specific components)
step1 : corelib
	$(MAKE) -C Core allgameserver
	$(MAKE) -C server allgameserver
#	$(MAKE) -C util

# Step 2: Build loginserver (only server-specific components)
step2 : corelib
	$(MAKE) -C Core allloginserver
	$(MAKE) -C server allloginserver
#	$(MAKE) -C util

# Step 3: Build sharedserver (only server-specific components)
step3 : corelib
	$(MAKE) -C Core allsharedserver
	$(MAKE) -C server allsharedserver

# Build gameserver (Core + server components)
allgameserver : corelib
	$(MAKE) -C Core allgameserver
	$(MAKE) -C server allgameserver
#	$(MAKE) -C util

# Build loginserver (Core + server components)
allloginserver : corelib
	$(MAKE) -C Core allloginserver
	$(MAKE) -C server allloginserver
#	$(MAKE) -C util

# Build sharedserver (Core + server components)
allsharedserver : corelib
	$(MAKE) -C Core allsharedserver
	$(MAKE) -C server allsharedserver

alltheoneserver :
	$(MAKE) -C Core alltheoneserver
	$(MAKE) -C server alltheoneserver

clean :
	$(MAKE) -C Core clean
	$(MAKE) -C server clean
#$(MAKE) -C util clean

cleanloginserver :
	$(MAKE) -C Core cleanloginserver
	$(MAKE) -C server cleanloginserver
#	$(MAKE) -C util cleanloginserver

cleangameserver :
	$(MAKE) -C Core cleangameserver
	$(MAKE) -C server cleangameserver
#	$(MAKE) -C util cleangameserver

cleanall :
	make cleanbin
	make cleanlib
	make cleanlog
	$(MAKE) -C Core cleanall
	$(MAKE) -C server cleanall
#	$(MAKE) -C util cleanall

cleanbin :
	rm -rf $(BINDIR)/gameserver $(BINDIR)/loginserver $(BINDIR)/gameclient \
			$(BINDIR)/updateserver $(BINDIR)/updateclient

cleanlib :
	rm -rf $(LIBDIR)/*.a $(LIBDIR)/st*

cleanlog :
	rm -rf $(LOGDIR)/*.log 

wc :
	wc `find . -name "*.h" -o -name "*.cpp"`
